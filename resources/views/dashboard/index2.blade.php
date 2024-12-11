@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
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
                    <h5 class="card-title">Total Transactions ({{ $currentYear }})</h5>
                    <p class="card-text">{{ $transactionCount }}</p>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Pemasukan Per Bulan ({{ $currentYear }})</h5>
                        <select id="yearFilter" class="form-control w-auto">
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <canvas id="revenueChart" height="100"></canvas>
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
        const ctx = document.getElementById('revenueChart').getContext('2d');

        const chartData = {
            labels: [
                'January', 'February', 'March', 'April', 'May',
                'June', 'July', 'August', 'September', 'October',
                'November', 'December'
            ],
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($revenueData->values()) !!},
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        };

        const options = {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            if (value >= 1e6) return `Rp ${(value / 1e6).toFixed(1)}m`; // jutaan
                            if (value >= 1e3) return `Rp ${(value / 1e3).toFixed(1)}k`; // ribuan
                            return `Rp ${value}`; // satuan
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1e6) return `${(value / 1e6).toFixed()}m`;
                            if (value >= 1e3) return `${(value / 1e3).toFixed()}k`;
                            return value;
                        }
                    },
                    title: {
                        display: true,
                        text: 'Pemasukan (Rp)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        };

        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: options
        });

        // Event Listener untuk Filter Tahun
        document.getElementById('yearFilter').addEventListener('change', function () {
            const selectedYear = this.value;
            window.location.href = `?year=${selectedYear}`;
        });
    });
</script>
@endsection
