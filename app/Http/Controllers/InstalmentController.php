<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Instalment;
use App\Models\Payment;

class InstalmentController extends Controller
{
    public function pay(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'instalment_ids' => 'required|array',
                'instalment_ids.*' => 'exists:instalments,id',
            ]);

            // Ambil instalments berdasarkan ID yang dikirim
            $instalments = Instalment::whereIn('id', $validated['instalment_ids'])
                ->orderBy('due_date')
                ->get();

            if ($instalments->isEmpty()) {
                return response()->json(['message' => 'No instalments found.'], 404);
            }

            $transaction = $instalments->first()->transaction;

            // Validasi pembayaran berurutan
            foreach ($instalments as $instalment) {
                if ($instalment->paid_at) {
                    return response()->json(['message' => 'One or more instalments have already been paid.'], 400);
                }

                $previousInstalment = $transaction->instalments()
                    ->where('id', '<', $instalment->id)
                    ->whereNull('paid_at')
                    ->first();

                if (count($instalments) === 1 && $previousInstalment) {
                    // Debug log untuk instalment yang belum dibayar
                    Log::info('Previous instalment not paid:', [
                        'current_instalment' => $instalment->id,
                        'previous_instalment' => $previousInstalment->id,
                    ]);

                    return response()->json(['message' => 'Please complete previous instalments first.'], 400);
                }
            }

            // Hitung total pembayaran
            $totalAmount = $instalments->sum('total');

            // Buat payment record
            $payment = Payment::create([
                'user_id' => $request->user_id,
                'reference_id' => null,
                'reference_type' => 'instalment',
                'invoice_number' => uniqid('CASH-'),
                'amount' => $totalAmount,
                'payment_method' => 'cash',
                'status' => 'paid',
                'trigger_by' => 'admin',
                'paid_at' => now(),
            ]);

            // Tandai instalments sebagai dibayar
            foreach ($instalments as $instalment) {
                $instalment->update(['paid_at' => now()]);

                // Masukkan ke tabel pivot
                DB::table('payment_instalment')->insert([
                    'payment_id' => $payment->id,
                    'instalment_id' => $instalment->id,
                    'amount' => $instalment->total,
                ]);
            }

            // Update status transaksi
            $totalInstalments = $transaction->instalments()->count();
            $paidInstalments = $transaction->instalments()->whereNotNull('paid_at')->count();

            $transaction->update([
                'status' => $paidInstalments === $totalInstalments
                    ? 'paid'
                    : ($paidInstalments > 0 ? 'partial' : 'unpaid'),
            ]);

            // Kembalikan response JSON
            return response()->json([
                'message' => 'Payment successful.',
                'payment' => $payment,
                'transaction' => $transaction,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
