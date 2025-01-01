@extends('layouts.frontend')


@section('content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title">Tagihan</h3>
            <p class="text-muted mb-0">Hingga tanggal <strong>01-07-2025</strong> <a href="#"><i class="bi bi-arrow-repeat"></i></a></p>
        </div>
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card p-4">
                    <p class="summary-text">0 tagihan pembayaran</p>
                    <h1 class="amount">Rp 0</h1>
                    <p class="subtext">Nol Rupiah</p>
                    <hr>
                    <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-outline-primary">Lihat Rekapan <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted">Anda tidak memiliki tagihan</p>
            </div>
        </div>
@endsection