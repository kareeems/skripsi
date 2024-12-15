@extends('layouts.dashboard')
@section('breadcrumb', isset($item) ? 'Edit Categori Tagihan' : 'Create Categori Tagihan')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>{{ isset($item) ? 'Edit' : 'Create' }} Categori Tagihan</h1>

    <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST">
        @csrf
        @if (isset($item))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ isset($item) ? $item->name : '' }}" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount"
                value="{{ isset($item) ? $item->amount : '' }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="pondok" {{ isset($item) && $item->type == 'pondok' ? 'selected' : '' }}>Pondok</option>
                <option value="sekolah" {{ isset($item) && $item->type == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
