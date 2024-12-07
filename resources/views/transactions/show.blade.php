@extends('layouts.dashboard')

@section('breadcrumb', isset($item) ? 'Edit Categori Tagihan' : 'Create Categori Tagihan')

@section('content')
        <h1>Transaction Details</h1>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $transaction->id }}</td>
            </tr>
            <tr>
                <th>User</th>
                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Subtotal</th>
                <td>{{ $transaction->subtotal }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td>{{ $transaction->total }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($transaction->status) }}</td>
            </tr>
        </table>

        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
@endsection
