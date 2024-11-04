@extends('layouts.dashboard')

@section('breadcrumb', 'Categori Tagihan')

@section('content')
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Categori Tagihan</h1>
        <a href="{{ route('items.create') }}" class="btn btn-primary">Add New Item</a>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $items->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection

@section('script')
<script>
    function confirmDelete(item_id) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda Tidak Akan Dapat Mengembalikannya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika dikonfirmasi
                document.getElementById('delete-form-' + item_id).submit();
            } else {
                Swal.fire("Dibatalkan", "Item tidak dihapus", "error");
            }
        });
    }
</script>
@endsection
