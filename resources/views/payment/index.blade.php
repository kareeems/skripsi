@extends('layouts.dashboard')

@section('breadcrumb', 'Managemen Transaksi')

@section('style')
<style>
    .json-pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Managemen Transaksi</h1>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('payments.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Search -->
            <div class="col-md-4 mb-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Search by invoice number or method">
            </div>

            <!-- Status Filter -->
            <div class="col-md-3 mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Created At Range -->
            <div class="col-md-5 mb-3">
                <label class="form-label">Created At</label>
                <div class="d-flex">
                    <input type="date" name="start_date" class="form-control me-2"
                        value="{{ request('start_date') }}" placeholder="Start Date">
                    <input type="date" name="end_date" class="form-control"
                        value="{{ request('end_date') }}" placeholder="End Date">
                </div>
                <small class="text-muted">Select a date range to filter records.</small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    <!-- Payments Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>User</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Referensi</th>
                <th>Created At</th>
                <th>Callback</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $payment->invoice_number }}</td>
                    <td>{{ $payment->user->getFullNameAttribute() ?? 'N/A' }}</td>
                    <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                    <td>{{ $payment->instalment->description ?? 'N/A' }}</td>
                    <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#callbackModal{{ $payment->id }}">
                            View
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="callbackModal{{ $payment->id }}" tabindex="-1" aria-labelledby="callbackModalLabel{{ $payment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="callbackModalLabel{{ $payment->id }}">Invoice: {{ $payment->invoice_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Store the callback data in data-attribute -->
                                        <pre id="jsonView{{ $payment->id }}" data-callback-data="{{ json_encode($payment->callback_data) }}" style="white-space: pre-wrap; word-wrap: break-word;"></pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $payments->links() }}
    </div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Loop through each modal and process its data-callback-data attribute
        @foreach($payments as $payment)
            const jsonView{{ $payment->id }} = document.getElementById('jsonView{{ $payment->id }}');

            if (jsonView{{ $payment->id }}) {
                const callbackData = JSON.parse(jsonView{{ $payment->id }}.getAttribute('data-callback-data'));

                if (callbackData && Object.keys(callbackData).length > 0) {
                    // Format the callback data into a readable format
                    let formattedData = JSON.stringify(callbackData, null, 2)
                        .replace(/{/g, "")  // Menghapus tanda {
                        .replace(/}/g, "")  // Menghapus tanda }
                        .replace(/\\/g, "") // Menghapus tanda \
                        .replace(/",/g, '"\n'); // Menambahkan enter setelah koma

                    // Display the formatted data inside the pre tag
                    jsonView{{ $payment->id }}.textContent = formattedData;
                } else {
                    // Show a message if no callback data exists
                    jsonView{{ $payment->id }}.textContent = "No callback data available.";
                }
            }
        @endforeach
    });
</script>
@endsection
