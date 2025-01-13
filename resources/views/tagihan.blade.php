@extends('layouts.frontend')


@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="page-title">Tagihan</h3>
        <p class="text-muted mb-0">
            Hingga tanggal <strong>{{ now()->format('d-m-Y') }}</strong>
            <a href="/tagihan"><i class="bi bi-arrow-repeat"></i></a>
        </p>
    </div>
    <div class="row">
        <div class="col-lg mx-auto">
            <div class="card p-4">
                <p class="summary-text">
                    @if ($totalAmount > 0)
                        {{ number_format($totalInstalment, 0, ',', '.') }} tagihan belum dibayar
                    @else
                        0 tagihan pembayaran
                    @endif
                </p>
                <h1 class="amount">
                    @if ($totalAmount > 0)
                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                    @else
                        Rp 0
                    @endif
                </h1>
                <p class="subtext">
                    @if ($totalAmount > 0)
                        {{ $terbilang }} Rupiah
                    @else
                        Nol Rupiah
                    @endif
                </p>
                <hr>
                <div class="d-flex justify-content-start">
                    <a href="#" class="btn btn-outline-primary">Lihat Rekapan <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            @if ($totalAmount > 0)
    <div class="mt-4">
        <!-- Section Total dan Tombol Bayar -->
        <div id="action-section" class="d-none alert alert-success">
            <h5>Total yang akan dibayar:</h5>
            <h3 class="text-primary fw-bold" id="total-preview">Rp 0</h3>
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-success" id="pay-now" disabled>Bayar Sekarang</button>
            </div>
        </div>

        <h5 class="mb-3">Daftar Tagihan:</h5>
        <form id="payment-form" action="#" method="POST">
            @csrf
            <div id="tagihan-list">
                @foreach ($instalments as $instalment)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        Rp {{ number_format($instalment->total, 0, ',', '.') }}
                                    </h5>
                                    <h6 class="mb-1 text-muted">
                                        Tagihan: {{ $instalment->description ?? 'Tidak ada deskripsi' }}
                                    </h6>
                                    <p class="mb-0 text-muted">Jatuh Tempo: {{ $instalment->due_date->format('d-m-Y') }}</p>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-select" data-id="{{ $instalment->id }}" data-amount="{{ $instalment->total }}">
                                    Pilih
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
@else
    <p class="text-center mt-3 text-muted">Anda tidak memiliki tagihan</p>
@endif
        </div>
    </div>
@endsection

@section('script')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.btn-select');
        const totalPreview = document.getElementById('total-preview');
        const payNowButton = document.getElementById('pay-now');
        const actionSection = document.getElementById('action-section');
        const form = document.getElementById('payment-form');
        let selected = new Set(); // Menyimpan ID tagihan yang dipilih

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const cardBody = button.closest('.card-body');
                const id = button.dataset.id;
                const amount = parseInt(button.dataset.amount);

                // Toggle selection
                if (selected.has(id)) {
                    selected.delete(id);
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-outline-primary');
                    button.textContent = 'Pilih';
                    cardBody.style.backgroundColor = ''; // Reset background
                } else {
                    selected.add(id);
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-danger');
                    button.textContent = 'Batalkan';
                    cardBody.style.backgroundColor = '#E3F2FD'; // Biru muda
                }

                // Hitung total
                let total = 0;
                selected.forEach(selectedId => {
                    const btn = Array.from(buttons).find(b => b.dataset.id === selectedId);
                    if (btn) total += parseInt(btn.dataset.amount);
                });

                // Update tampilan total
                totalPreview.textContent = 'Rp ' + total.toLocaleString('id-ID');

                // Tampilkan/hilangkan action section
                if (selected.size > 0) {
                    actionSection.classList.remove('d-none');
                } else {
                    actionSection.classList.add('d-none');
                }

                // Update tombol form
                payNowButton.disabled = selected.size === 0;

                // Update form hidden input untuk dikirimkan
                updateFormInputs();
            });
        });

        // Fungsi untuk memperbarui input tersembunyi di dalam form
        function updateFormInputs() {
            // Hapus semua input tersembunyi sebelumnya
            document.querySelectorAll('input[name="instalments[]"]').forEach(input => input.remove());

            // Tambahkan input tersembunyi baru berdasarkan ID yang dipilih
            selected.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'instalments[]';
                input.value = id;
                form.appendChild(input);
            });
        }
    });
</script>
@endsection
