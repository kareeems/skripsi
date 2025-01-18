@extends('layouts.frontend')

@section('content')
    <h3>Riwayat Pembayaran</h3>
    <p>Di bawah ini adalah data riwayat pembayaran yang pernah Anda lakukan.</p>
    <small class="text-muted">Menampilkan {{ $payments->firstItem() }}-{{ $payments->lastItem() }} dari {{ $payments->total() }} item.</small>

    <div class="riwayat mt-3">
        @forelse ($payments as $payment)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row">
                        <!-- Bagian Kiri -->
                        <div class="col order-last order-md-first">
                            <dl class="row mb-0">
                                <dt class="col-12 col-sm-6 col-md-12 col-lg-5 col-xl-3 fw-normal text-secondary">
                                    <small>No. Transaksi</small>
                                </dt>
                                <dd class="col-12 col-sm-6 col-md-12 col-lg-7 col-xl-9">
                                    <span class="fw-bold">{{ $payment->invoice_number }}</span>
                                </dd>

                                <dt class="col-12 col-sm-6 col-md-12 col-lg-5 col-xl-3 fw-normal text-secondary">
                                    <small>Tanggal Pembayaran</small>
                                </dt>
                                <dd class="col-12 col-sm-6 col-md-12 col-lg-7 col-xl-9">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                </dd>
                            </dl>
                        </div>

                        <!-- Garis Pembatas -->
                        <div class="vr text-secondary d-none d-md-block mx-3"></div>
                        <hr class="text-secondary my-2 d-block d-md-none">

                        <!-- Bagian Kanan -->
                        <div class="col order-first order-md-last">
                            <div class="d-flex justify-content-between flex-md-column">
                                <dl class="mb-0 mb-md-2">
                                    <dt class="fw-normal text-secondary">
                                        <small>Nominal</small>
                                    </dt>
                                    <dd class="mb-0">
                                        <small class="align-top text-secondary">Rp</small>
                                        <span class="fw-normal">{{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </dd>
                                </dl>
                                <p class="my-auto mb-md-0">
                                    <a href="{{ route("show.riwayat", $payment->id) }}" class="stretched-link text-decoration-none">
                                        <small>
                                            <span class="me-1 me-sm-0">Lihat Lebih Lengkap</span>
                                            <i class="bi bi-arrow-right d-none d-sm-inline-block"></i>
                                        </small>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada riwayat pembayaran.</p>
        @endforelse
    </div>

    <!-- Paginasi -->
    <div class="mt-3">
        {{ $payments->links('pagination::bootstrap-4') }}
    </div>
@endsection
