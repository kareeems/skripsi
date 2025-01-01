@extends('layouts.frontend')

@section('css')
<style>
.transaction-list {
    max-height: 400px; /* Tentukan tinggi maksimal untuk scroll */
    overflow-y: auto;  /* Menambahkan scroll vertikal jika konten lebih banyak */
}
</style>


@section('content')
<h3>Transaksi Online</h3>
<p>Di bawah ini adalah data riwayat transaksi online yang pernah Anda lakukan.</p>
<small class="text-muted">Menampilkan 1-10 dari 22 item.</small>

<div class="filter-buttons mt-3">
    <button class="btn btn-outline-secondary">Semua</button>
    <button class="btn btn-outline-secondary">Menunggu Pembayaran</button>
    <button class="btn btn-outline-secondary">Berhasil</button>
    <button class="btn btn-outline-secondary">Dibatalkan / Kadaluarsa</button>
</div>

<div class="transaction-list mt-3">
    <!-- Transaksi 1 -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div>
                <p><strong>VA.215411100.1730702758</strong></p>
                <small>dibuat satu bulan yang lalu</small>
                <p><strong>Metode Pembayaran</strong><br>BNI Virtual Account</p>
                <p><strong>Kode Pembayaran</strong><br>9881041221411000</p>
            </div>
            <div class="text-end">
                <p><strong>Total Pembayaran</strong><br>Rp 5.002.000</p>
                <span class="badge badge-success">Berhasil</span>
            </div>
        </div>
    </div>

    <!-- Transaksi 2 -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div>
                <p><strong>VA.215411100.1724896323</strong></p>
                <small>dibuat 4 bulan yang lalu</small>
                <p><strong>Metode Pembayaran</strong><br>BNI Virtual Account</p>
                <p><strong>Kode Pembayaran</strong><br>9881041221411000</p>
            </div>
            <div class="text-end">
                <p><strong>Total Pembayaran</strong><br>Rp 1.502.000</p>
                <span class="badge badge-success">Berhasil</span>
            </div>
        </div>
    </div>

    <!-- Transaksi 3 -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div>
                <p><strong>VA.215411100.1720408328</strong></p>
                <small>dibuat 5 bulan yang lalu</small>
                <p><strong>Metode Pembayaran</strong><br>BNI Virtual Account</p>
                <p><strong>Kode Pembayaran</strong><br>9881041221411000</p>
            </div>
            <div class="text-end">
                <p><strong>Total Pembayaran</strong><br>Rp 3.002.000</p>
                <span class="badge badge-success">Berhasil</span>
            </div>
        </div>
    </div>
</div>

@endsection