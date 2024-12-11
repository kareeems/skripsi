@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
        <!-- Filter Tahun -->
        <div class="col-md-12 mb-4">
            {{-- <form action="{{ route('dashboard.index') }}" method="GET" class="form-inline"> --}}
                <label for="year" class="mr-2">Filter Year:</label>
                <select name="year" id="year" class="form-control mr-2">
                    @foreach (range(now()->year - 5, now()->year) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            {{-- </form> --}}
        </div>

        <!-- Ringkasan Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ $userCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <p class="card-text">{{ $itemCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Transactions ({{ $year }})</h5>
                    <p class="card-text">{{ $transactionCount }}</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-md-12 mb-4">
            <div class="alert alert-info">
                <strong>Total Revenue ({{ $year }}):</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
        </div>

        <!-- Grafik -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transactions Per Month ({{ $year }})</h5>
                    <canvas id="transactionsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'January', 'February', 'March', 'April', 'May',
                    'June', 'July', 'August', 'September', 'October',
                    'November', 'December'
                ],
                datasets: [{
                    label: 'Transactions',
                    data: {!! json_encode($transactionData->values()) !!},
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Transactions: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Transactions Count'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
