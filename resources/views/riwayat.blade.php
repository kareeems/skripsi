@extends('layouts.frontend')
@section('css')
<style>
.riwayat {
    max-height: 400px; /* Tentukan tinggi maksimal untuk scroll */
    overflow-y: auto;  /* Menambahkan scroll vertikal jika konten lebih banyak */
}
</style>
@section('content')
        <h3>Riwayat Pembayaran</h3>
        <p>Di bawah ini adalah data riwayat pembayaran yang pernah Anda lakukan</p>
        <small class="text-muted">Menampilkan 1-20 dari 21 item.</small>

        <div class="riwayat mt-3">
            <!-- Riwayat Pembayaran 1 -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div>
                        <p><strong>No. Transaksi</strong><br>VA.215411100.1730702758</p>
                        <p><strong>Tanggal Pembayaran</strong><br>04/11/2024</p>
                    </div>
                    <div class="text-end">
                        <p><strong>Nominal</strong><br>Rp 5.002.000</p>
                        <a href="#" class="more-link">Lihat Lebih Lengkap &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembayaran 2 -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div>
                        <p><strong>No. Transaksi</strong><br>VA.215411100.1724896323</p>
                        <p><strong>Tanggal Pembayaran</strong><br>29/08/2024</p>
                    </div>
                    <div class="text-end">
                        <p><strong>Nominal</strong><br>Rp 1.502.000</p>
                        <a href="#" class="more-link">Lihat Lebih Lengkap &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembayaran 3 -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div>
                        <p><strong>No. Transaksi</strong><br>VA.215411100.1720408328</p>
                        <p><strong>Tanggal Pembayaran</strong><br>08/07/2024</p>
                    </div>
                    <div class="text-end">
                        <p><strong>Nominal</strong><br>Rp 3.002.000</p>
                        <a href="#" class="more-link">Lihat Lebih Lengkap &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
@endsection