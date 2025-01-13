<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instalment;

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
        $totalUnpaid = Instalment::whereNull('paid_at')->sum('total');

        return view('home', compact('user', 'totalUnpaid'));
    }

    public function tagihan()
    {
        // Hitung total tagihan yang belum dibayar (paid_at = null)
        $totalUnpaid = Instalment::whereNull('paid_at');

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
        return view('riwayat');
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
