@extends('layouts.frontend')

@section('content')
    <h3 class="mb-4">Riwayat Pembayaran</h3>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <dl class="row">
                <!-- Informasi Transaksi -->
                <dt class="col-12 col-sm-4 text-secondary"><small>No. Transaksi</small></dt>
                <dd class="col-12 col-sm-8 mb-3">
                    <strong>{{ $payment->invoice_number ?? 'N/A' }}</strong>
                </dd>

                <dt class="col-12 col-sm-4 text-secondary"><small>Tanggal Pembayaran</small></dt>
                <dd class="col-12 col-sm-8 mb-3">
                    {{ $payment->paid_at->format('Y-m-d') }}
                </dd>

                <dt class="col-12 col-sm-4 text-secondary"><small>Pembayaran</small></dt>
                <dd class="col-12 col-sm-8 mb-3">
                    <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                </dd>
            </dl>
        </div>
    </div>
    <small class="text-muted">Menampilkan {{ $payment->instalments->firstItem() }} - {{ $payment->instalments->lastItem() }} dari {{ $payment->instalments->total() }} item.</small>

    <!-- Detail Instalments -->
    <div class="card shadow-sm">
        <table class="table table-borderless mt-3">
            <thead>
                <tr>
                    <th class="text-secondary">Nama Biaya</th>
                    <th class="text-secondary">Tagihan</th>
                    <th class="text-secondary">Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payment->instalments as $instalment)
                    <tr>
                        <td>
                            <div>{{ $instalment->fee_name }}</div>
                            <small class="text-muted">
                                Semester {{ $instalment->semester }} • Periode {{ $instalment->period }}
                            </small>
                        </td>
                        <td>Rp {{ number_format($instalment->pivot->amount ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($instalment->pivot->penalty ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Tidak ada instalmen terkait pembayaran ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
