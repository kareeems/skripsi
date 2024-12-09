@extends('layouts.dashboard')
@section('breadcrumb', 'Transaction Details')

@section('content')
    <h1>Transaction Details</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="2" class="text-center">Transaction Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $transaction->id }}</td>
            </tr>
            <tr>
                <th>User</th>
                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($transaction->status) }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Transaction Items</h3>
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
        </tfoot>
    </table>

    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
@endsection
