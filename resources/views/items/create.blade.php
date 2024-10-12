@extends('layouts.dashboard')

@section('breadcrumb', 'Add Transaction Item')

@section('content')
    <h1>Add Transaction Item</h1>

    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_name">Item Name:</label>
            <input type="text" class="form-control" name="item_name" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" class="form-control" name="amount" required>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <select class="form-control" name="type" required>
                <option value="pondok">Pondok</option>
                <option value="sekolah">Sekolah</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Item</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
