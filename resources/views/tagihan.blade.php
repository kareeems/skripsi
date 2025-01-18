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
                    <a href="/riwayat" class="btn btn-outline-primary">Lihat Rekapan <i class="bi bi-arrow-right"></i></a>
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
                                                <p class="mb-0 text-muted">Jatuh Tempo:
                                                    {{ $instalment->due_date->format('d-m-Y') }}</p>
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
                    </form>
                </div>
            @else
                <p class="text-center mt-3 text-muted">Anda tidak memiliki tagihan</p>
            @endif
        </div>
    </div>

    <div id="loading-indicator"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@section('script')
    <!-- Tambahkan script Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.btn-select');
            const totalPreview = document.getElementById('total-preview');
            const payNowButton = document.getElementById('pay-now');
            const actionSection = document.getElementById('action-section');
            const form = document.getElementById('payment-form');
            let selectedInstalments = []; // Menyimpan ID instalment yang dipilih
            let totalAmount = 0; // Menyimpan jumlah total yang dipilih
            let currentIndex = 0; // Indeks terakhir yang dipilih

            buttons.forEach(button => {
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
                        // Validasi urutan pemilihan berdasarkan index yang terurut
                        const currentInstalmentIndex = Array.from(buttons)
                            .findIndex(btn => btn.dataset.id == instalmentId);

                        // Pastikan pemilihan dilakukan secara berurutan
                        if (currentInstalmentIndex !== currentIndex) {
                            alert(
                                'Harap pilih instalment secara berurutan berdasarkan tanggal jatuh tempo.');
                            return;
                        }

                        // Pilih instalment
                        card.classList.add('selected');
                        selectedInstalments.push(instalmentId);
                        totalAmount += instalmentAmount;
                        currentIndex++; // Update currentIndex ke instalment berikutnya
                        this.textContent = 'Batalkan';
                    }

                    // Update total
                    updateTotal();
                });
            });

            // Fungsi untuk memperbarui tampilan total
            function updateTotal() {
                totalPreview.textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');

                // Tampilkan/hilangkan action section
                if (selectedInstalments.length > 0) {
                    actionSection.classList.remove('d-none');
                } else {
                    actionSection.classList.add('d-none');
                }

                // Update tombol form
                payNowButton.disabled = selectedInstalments.length === 0;

                // Update form hidden input untuk dikirimkan
                updateFormInputs();
            }

            // Fungsi untuk memperbarui input tersembunyi di dalam form
            function updateFormInputs() {
                // Hapus semua input tersembunyi sebelumnya
                document.querySelectorAll('input[name="instalments[]"]').forEach(input => input.remove());

                // Tambahkan input tersembunyi baru berdasarkan ID yang dipilih
                selectedInstalments.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'instalments[]';
                    input.value = id;
                    form.appendChild(input);
                });
            }

            // Fungsi untuk menampilkan loading
            function showLoading() {
                const loadingElement = document.getElementById('loading-indicator');
                if (loadingElement) {
                    loadingElement.style.display = 'block'; // Tampilkan loading
                }

                // Disable semua tombol dengan class .pay-midtrans
                document.querySelectorAll('.pay-midtrans').forEach(button => {
                    button.disabled = true;
                });
            }

            // Fungsi untuk menyembunyikan loading
            function hideLoading() {
                const loadingElement = document.getElementById('loading-indicator');
                if (loadingElement) {
                    loadingElement.style.display = 'none'; // Sembunyikan loading
                }

                // Enable semua tombol dengan class .pay-midtrans
                document.querySelectorAll('.pay-midtrans').forEach(button => {
                    button.disabled = false;
                });
            }

            // Fungsi untuk menangani pembayaran dengan Midtrans
            payNowButton.addEventListener('click', function(event) {
                event.preventDefault();
                showLoading();

                // Jika belum ada instalment yang dipilih
                if (selectedInstalments.length === 0) {
                    alert('Harap pilih tagihan untuk dibayar.');
                    return;
                }

                // Ambil data yang diperlukan untuk Midtrans (contoh: totalAmount, selectedInstalments)
                const paymentData = {
                    user_id: `{{ auth()->user()->id }}`,
                    payment_type: 'instalment',
                    total_amount: totalAmount,
                    referensi_ids: selectedInstalments.map(i => Number(i)),
                    amount: totalAmount,
                    first_name: `{{ auth()->user()->first_name }}`,
                    last_name: `{{ auth()->user()->last_name }}`,
                    email: `{{ auth()->user()->email }}`,
                    phone: `{{ auth()->user()->phone }}`,
                };

                console.log(paymentData);

                // Kirim request ke server untuk memulai transaksi Midtrans
                fetch(`{{ route('payment.charge') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify(paymentData)
                    })
                    .then(response => {
                        console.log(response.clone().text());

                        hideLoading(); // Sembunyikan loading setelah menerima respons
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        snap.pay(data.token, {
                            onSuccess: function(result) {
                                alert('Payment successful!');
                                location.reload();
                            },
                            onPending: function(result) {
                                alert('Waiting for payment!');
                            },
                            onError: function(result) {
                                alert('Payment failed!');
                            },
                        });
                    })
                    .catch(error => console.error('Error:', error));
            });

        });
    </script>
@endsection
