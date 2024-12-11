<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instalment;

class InstalmentController extends Controller
{
    public function pay(Instalment $instalment)
    {
        if ($instalment->paid_at) {
            return redirect()->back()->with('error', 'This instalment has already been paid.');
        }

        // Validasi jika sebelumnya belum lunas
        $previousInstalment = $instalment->transaction->instalments()
            ->where('id', '<', $instalment->id)
            ->whereNull('paid_at')
            ->first();

        if ($previousInstalment) {
            return redirect()->back()->with('error', 'Please complete previous instalments first.');
        }

        // Tandai instalment sebagai dibayar
        $instalment->update([
            'paid_at' => now(),
        ]);

        // Ambil transaksi terkait
        $transaction = $instalment->transaction;

        // Hitung status pembayaran berdasarkan instalments
        $totalInstalments = $transaction->instalments()->count();
        $paidInstalments = $transaction->instalments()->whereNotNull('paid_at')->count();

        if ($paidInstalments === 0) {
            $transaction->update(['status' => 'unpaid']);
        } elseif ($paidInstalments < $totalInstalments) {
            $transaction->update(['status' => 'partial']);
        } else {
            $transaction->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', 'Instalment paid successfully.');
    }
}
