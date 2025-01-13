@extends('layouts.dashboard')
@section('breadcrumb', 'Instalments List')

@section('content')
    <h3>Instalments</h3>

    <!-- Tampilkan Total yang akan dibayar jika ada yang dipilih -->
    <div id="action-section" class="d-none alert alert-success">
        <h5>Total yang akan dibayar:</h5>
        <h3 class="fw-bold" id="total-preview">Rp 0</h3>
        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-success" id="pay-now" disabled>Bayar Sekarang</button>
        </div>
    </div>

    <div id="tagihan-list">
        <!-- Belum dibayar -->
        <div class="mb-2">
            @foreach ($transaction->instalments->whereNull('paid_at') as $index => $instalment)
                <div class="card mb-3 shadow-sm" id="instalment-{{ $instalment->id }}">
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
                            <button type="button" class="btn btn-outline-primary btn-select"
                                data-id="{{ $instalment->id }}" data-amount="{{ $instalment->total }}">
                                Pilih
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sudah dibayar -->
        <div class="mb-2">
            @foreach ($transaction->instalments->whereNotNull('paid_at') as $index => $instalment)
                <div class="card mb-3 shadow-sm" id="instalment-{{ $instalment->id }}">
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
                            <button type="button" class="btn btn-outline-secondary" disabled>Sudah Dibayar</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">Back</a>

    <!-- Modal Konfirmasi Pembayaran -->
<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentConfirmationModalLabel">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membayar tagihan ini menggunakan metode pembayaran Cash?</p>
                <p><strong>Total yang akan dibayar: <span id="total-preview-modal">Rp 0</span></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmPaymentButton">Bayar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
    <style>
        .card.selected {
            background-color: #e1f5fe;
            /* Biru muda */
        }
    </style>
@endsection

@section('script')
    <script>
        const confirmPaymentButton = document.getElementById('confirmPaymentButton');
        const totalPreviewModal = document.getElementById('total-preview-modal');
        const totalPreview = document.getElementById('total-preview');
        const payNowButton = document.getElementById('pay-now');
        const actionSection = document.getElementById('action-section');

        let selectedInstalments = [];
        let totalAmount = 0;
        let currentIndex = 0;

        function updateTotal() {
            totalPreview.textContent = 'Rp ' + totalAmount.toLocaleString();

            if (selectedInstalments.length > 0) {
                actionSection.classList.remove('d-none');
                payNowButton.disabled = false;
            } else {
                actionSection.classList.add('d-none');
                payNowButton.disabled = true;
            }
        }

        document.querySelectorAll('.btn-select').forEach(button => {
            button.addEventListener('click', function() {
                const instalmentId = this.dataset.id;
                const instalmentAmount = parseInt(this.dataset.amount);
                const card = document.getElementById('instalment-' + instalmentId);

                // Cek apakah instalment sudah dipilih
                if (card.classList.contains('selected')) {
                    // Batalkan pemilihan
                    card.classList.remove('selected');
                    selectedInstalments = selectedInstalments.filter(id => id !== instalmentId);
                    totalAmount -= instalmentAmount;
                    currentIndex--; // Sesuaikan currentIndex jika membatalkan
                    this.textContent = 'Pilih';
                } else {
                    // Validasi urutan pemilihan
                    const currentInstalmentIndex = Array.from(document.querySelectorAll('.btn-select'))
                        .findIndex(btn => btn.dataset.id == instalmentId);

                    // Pastikan pemilihan berurutan berdasarkan index yang sudah terurut
                    if (currentInstalmentIndex !== currentIndex) {
                        alert('Harap pilih instalment secara berurutan berdasarkan tanggal jatuh tempo.');
                        return;
                    }

                    // Pilih instalment
                    card.classList.add('selected');
                    selectedInstalments.push(instalmentId);
                    totalAmount += instalmentAmount;
                    currentIndex++; // Update currentIndex ke instalment berikutnya
                    this.textContent = 'Batalkan';
                }

                updateTotal();
            });
        });

        const paymentConfirmationModal = new bootstrap.Modal(document.getElementById('paymentConfirmationModal'));

        // Event listener untuk tombol Bayar Sekarang
        payNowButton.addEventListener('click', function () {
            if (selectedInstalments.length > 0) {
                const totalPreviewElement = document.getElementById('total-preview-modal');

                if (totalPreviewElement) {
                    totalPreviewElement.textContent = 'Rp ' + totalAmount.toLocaleString();
                }

                paymentConfirmationModal.show();
            }
        });

        // Event listener untuk tombol konfirmasi pembayaran di modal
        confirmPaymentButton.addEventListener('click', function() {
            // Panggil fungsi payInstalments untuk memproses pembayaran
            payInstalments(selectedInstalments);

            // Tutup modal setelah pembayaran diproses
            paymentConfirmationModal.hide();
        });

        // Fungsi untuk memproses pembayaran
        async function payInstalments(instalmentIds) {
            try {
                const instalment_ids = instalmentIds.map(i=>Number(i));

                const response = await fetch(`{{ route('instalments.pay') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ instalment_ids, user_id: `{{ $transaction->user_id }}` }),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to process payment');
                }

                alert('Payment successful!');
                window.location.reload();
            } catch (error) {
                alert('Payment unsuccessful!');
                console.log(`Error: ${error.message}`);
            }
        }
    </script>
@endsection
