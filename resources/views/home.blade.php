@extends('layouts.frontend')

@section('css')

</style>
@section('content')
        <div class="text-center mb-4">
            <h3 class="profile-info">MUHAMMAD ABDUL KARIM</h3>
            <p>215411100 — Informatika 📱 085781462984 ✉️ kareemsyukur@gmail.com</p>
            <small class="text-muted">* Jika ada kesalahan pada data di atas, silakan hubungi bagian Akademik</small>
        </div>

        <div class="alert alert-light text-center border" role="alert">
            <strong>Anda tidak punya tagihan ✨</strong>
            <div>
                <a href="#" class="text-decoration-none me-3">Lihat Rekapan Tagihan</a>
                <a href="#" class="text-decoration-none">Lihat Riwayat Pembayaran</a>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <!-- Membuat seluruh kartu menjadi tautan -->
                <a href="tagihan" class="card-link">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-list-ul card-icon"></i>
                            <p class="card-title">Tagihan</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="transaksi_online" class="card-link">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-credit-card-2-front card-icon"></i>
                            <p class="card-title">Transaksi Online</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="riwayat" class="card-link">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-journal-text card-icon"></i>
                            <p class="card-title">Riwayat Pembayaran</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="bantuan" class="card-link">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-question-circle card-icon"></i>
                            <p class="card-title">Bantuan</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
@endsection