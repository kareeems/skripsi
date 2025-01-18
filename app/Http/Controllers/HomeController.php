<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instalment;
use App\Models\Payment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();

        // Hitung total tagihan untuk installment yang belum dibayar
        $totalUnpaid = Instalment::whereHas('transaction', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNull('paid_at')->sum('total');

        return view('home', compact('user', 'totalUnpaid'));
    }

    public function tagihan()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();

        // Hitung total tagihan yang belum dibayar (paid_at = null)
        $totalUnpaid = Instalment::whereHas('transaction', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNull('paid_at');

        // Hitung total tagihan keseluruhan
        $totalAmount = $totalUnpaid->sum('total');

        // Hitung total tagihan
        $totalInstalment = $totalUnpaid->count();

        // Ambil semua cicilan
        $instalments = $totalUnpaid->get();

        // Penyebutan nominal
        $terbilang = $this->terbilang($totalAmount);

        return view('tagihan', compact('totalInstalment', 'totalAmount', 'instalments', 'terbilang'));
    }

    public function riwayat()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();

        // Hitung total tagihan instalment yang belum dibayar milik pengguna
        $payments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('riwayat', compact('payments'));
    }

    public function show_riwayat(Payment $payment)
    {
        $payment->load('instalments'); // Eager load instalments if needed
        $payment->instalments = $payment->instalments()->paginate(10);

        return view('show_riwayat', compact('payment'));
    }

    public function transaksi()
    {
        return view('transaksi_online');
    }

    public function bantuan()
    {
        return view('bantuan');
    }

    public function terbilang($number)
    {
        $number = abs($number);
        $words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        $units = ['', 'Ribu', 'Juta', 'Miliar', 'Triliun'];

        if ($number < 12) {
            return $words[$number];
        } elseif ($number < 20) {
            return $words[$number - 10] . ' Belas';
        } elseif ($number < 100) {
            return $words[floor($number / 10)] . ' Puluh ' . $this->terbilang($number % 10);
        } elseif ($number < 1000) {
            return ($number < 200 ? 'Seratus' : $words[floor($number / 100)] . ' Ratus') . ' ' . $this->terbilang($number % 100);
        }

        $i = 0;
        $result = '';

        while ($number > 0) {
            $chunk = $number % 1000;
            if ($chunk > 0) {
                $result = $this->terbilang($chunk) . ' ' . $units[$i] . ' ' . $result;
            }
            $number = floor($number / 1000);
            $i++;
        }

        return trim($result);
    }

}
