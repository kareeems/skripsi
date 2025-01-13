@extends('layouts.dashboard')
@section('breadcrumb', 'Transaction Details')

@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="2" class="text-center">Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>User ID</th>
                <td>{{ $transaction->id }}</td>
            </tr>
            <tr>
                <th>Fullname</th>
                <td>{{ $transaction->user->fullname ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($transaction->status) }}</td>
            </tr>
        </tbody>
    </table>

    <h5>Items:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->pivot->price, 2) }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                    <td>{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Subtotal:</th>
                <td>{{ number_format($transaction->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Total Amount:</th>
                <td>{{ number_format($transaction->total, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Action:</th>
                <td>
                    <a href="#" class="btn btn-sm btn-success">Bayar Semua</a>
                    <a href="{{ route('transaction.instalments', $transaction) }}" class="btn btn-sm btn-warning">Cicil</a>
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- <h3>Instalments</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Due Date</th>
                <th>Paid At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->instalments as $index => $instalment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $instalment->subtotal }}</td>
                    <td>{{ $instalment->total }}</td>
                    <td>{{ $instalment->due_date->format('d M Y') }}</td>
                    <td>{{ $instalment->paid_at ? $instalment->paid_at->format('d M Y H:i:s') : 'Unpaid' }}</td>
                    <td>
                        @if (!$instalment->paid_at)
                            <!-- Form untuk pembayaran langsung -->
                            <form action="{{ route('instalments.pay', $instalment) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Pay</button>
                            </form>

                            <!-- Tombol bayar dengan Midtrans -->
                            <button class="btn btn-primary btn-sm pay-midtrans" data-inst-id="{{ $instalment->id }}"
                                data-total="{{ $instalment->total }}">
                                Pay with Midtrans
                            </button>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>Paid</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
    <div id="loading-indicator" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> --}}
@endsection

@section('script')
    <!-- Tambahkan script Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
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

        document.querySelectorAll('.pay-midtrans').forEach(button => {
            button.addEventListener('click', function() {
                const instalmentId = this.dataset.instId;
                const total = this.dataset.total;

                showLoading(); // Tampilkan loading saat mulai proses

                fetch(`{{ route('payment.charge') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            instalment_id: instalmentId,
                            amount: parseInt(total),
                            user_id: '{{ $transaction->user->id }}',
                            first_name: '{{ $transaction->user->first_name }}',
                            last_name: '{{ $transaction->user->last_name }}',
                            email: '{{ $transaction->user->email }}',
                            phone: '{{ $transaction->user->phone }}'
                        }),
                    })
                    .then(response => {
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
