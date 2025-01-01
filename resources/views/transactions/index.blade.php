@extends('layouts.dashboard')

@section('breadcrumb', 'Managemen Transaksi')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Managemen Transaksi</h1>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">New Transaction</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user->fullname ?? 'N/A' }}</td>
                    <td>{{ $transaction->subtotal }}</td>
                    <td>{{ $transaction->total }}</td>
                    <td>
                        @if ($transaction->status === 'unpaid')
                            <span class="badge bg-danger">Unpaid</span>
                        @elseif ($transaction->status === 'partial')
                            <span class="badge bg-warning text-dark">Partial</span>
                        @elseif ($transaction->status === 'paid')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
