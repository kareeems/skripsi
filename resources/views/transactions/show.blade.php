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


    <h3>Instalments</h3>
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
                <td>{{ $instalment->paid_at ? $instalment->paid_at->format('d M Y') : 'Unpaid' }}</td>
                <td>
                    @if (!$instalment->paid_at)
                        <form action="{{ route('instalments.pay', $instalment) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Pay</button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>Paid</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
@endsection
