<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Instalment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createCharge(Request $request)
    {
        $request->validate([
            'instalment_id' => 'required|exists:instalments,id',
            'amount' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string|min:10',
        ]);

        $payment = Payment::create([
            'reference_id' => $request->instalment_id,
            'invoice_number' => uniqid('INV-'),
            'amount' => $request->amount,
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $payment->invoice_number,
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'params' => $params,
            'token' => $snapToken,
            'payment' => $payment,
        ]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $payment = Payment::where('invoice_number', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($request->transaction_status === 'settlement') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $request->payment_type,
                'callback_data' => $request->all(),
            ]);

            // Update instalment and related transaction
            $this->updateInstalmentStatus($payment->reference_id);
        }

        return response()->json(['message' => 'Payment updated']);
    }

    private function updateInstalmentStatus($instalmentId)
    {
        $instalment = Instalment::findOrFail($instalmentId);

        // Update paid_at pada instalment
        $instalment->update(['paid_at' => now()]);

        // Perbarui status transaksi terkait
        $this->updateTransactionStatus($instalment->transaction);

        return $instalment;
    }

    private function updateTransactionStatus($transaction)
    {
        $totalInstalments = $transaction->instalments()->count();
        $paidInstalments = $transaction->instalments()->whereNotNull('paid_at')->count();

        if ($paidInstalments === 0) {
            $transaction->update(['status' => 'unpaid']);
        } elseif ($paidInstalments < $totalInstalments) {
            $transaction->update(['status' => 'partial']);
        } else {
            $transaction->update(['status' => 'paid']);
        }

        return $transaction;
    }

}
