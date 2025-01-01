<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('home');
    }
    public function tagihan()
    {
        return view('tagihan');
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
    
}
