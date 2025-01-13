@extends('layouts.frontend')

@section('css')

</style>
@section('content')
        <div class="text-center mb-4">
            <h3 class="profile-info">{{ $user->getFullNameAttribute() }}</h3>
            <p>{{ $user->nis }} 📱 {{ $user->phone }} ✉️ {{ $user->email }}</p>
            <small class="text-muted">* Jika ada kesalahan pada data di atas, silakan hubungi bagian Akademik</small>
        </div>

        <div class="alert alert-{{ $totalUnpaid > 0 ? "danger":"light" }} text-center border" role="alert">
            @if ($totalUnpaid > 0)
                <h1>Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</h1>
                <div>
                    <a href="/tagihan" class="btn btn-warning text-decoration-none me-3">Lihat Tagihan Saya</a>
                </div>
            @else
                <strong>Anda tidak punya tagihan ✨</strong>
                <div>
                    <a href="/tagihan" class="text-decoration-none me-3">Lihat Rekapan Tagihan</a>
                    <a href="/riwayat" class="text-decoration-none">Lihat Riwayat Pembayaran</a>
                </div>
            @endif
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
