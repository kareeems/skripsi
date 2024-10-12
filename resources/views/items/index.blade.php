@extends('layouts.dashboard')

@section('breadcrumb', 'Transaction Items')

@section('content')
    <h1>Transaction Items</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('items.create') }}" class="btn btn-primary">Add New Item</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($item->type) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
