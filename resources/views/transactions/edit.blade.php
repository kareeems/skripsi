@extends('layouts.dashboard')

@section('breadcrumb', isset($item) ? 'Edit Categori Tagihan' : 'Create Categori Tagihan')

@section('content')
        <h1>Edit Transaction</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
    <label for="user_id" class="form-label">User</label>
    <select name="user_id" id="user_id" class="form-control" required>
        <option value="">-- Select User --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ $user->id == $transaction->user_id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
</div>

            <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="text" name="subtotal" id="subtotal" class="form-control" value="{{ $transaction->subtotal }}" required>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" name="total" id="total" class="form-control" value="{{ $transaction->total }}" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="unpaid" {{ $transaction->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
@endsection
