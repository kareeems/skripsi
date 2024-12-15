@extends('layouts.dashboard')
@section('breadcrumb', 'New Transaction')

@section('content')
    <h1>New Transaction</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Pilih Siswa</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">-- Select User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <!-- Item yang dapat dipilih (Checkbox dalam grid) -->
        <div class="mb-4">
            <h5 class="fw-bold">Kategori Pembayaran:</h5>
            <div class="row">
                @foreach ($items as $item)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input item-checkbox" type="checkbox" id="item_{{ $item->id }}"
                                value="{{ $item->id }}" data-name="{{ $item->name }}"
                                data-price="{{ $item->amount }}">
                            <label class="form-check-label" for="item_{{ $item->id }}">
                                {{ $item->name }} ({{ $item->amount }})
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Item yang sudah dipilih -->
        <div class="mb-4">
            <h5 class="fw-bold">Pembayaran Terpilih:</h5>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Amount</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="selected-items">
                    <!-- Baris akan ditambahkan di sini -->
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label for="subtotal" class="form-label">Subtotal</label>
            <input type="text" name="subtotal" id="subtotal" class="form-control" required readonly>
        </div>
        <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="text" name="total" id="total" class="form-control" required readonly>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="unpaid">Unpaid</option>
                <option value="paid">Paid</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection

@section('style')
    <style>
        table tbody tr {
            transition: all 0.3s ease-in-out;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        button.btn-outline-danger {
            transition: background-color 0.2s, color 0.2s;
        }

        button.btn-outline-danger:hover {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
@endsection

@section('script')
    <script>
        // Fungsi untuk menghitung subtotal dan total berdasarkan item yang dipilih
        function calculateTotals() {
            const selectedItems = document.querySelectorAll('#selected-items tr');
            let subtotal = 0;

            // Iterasi setiap item yang dipilih
            selectedItems.forEach(row => {
                const price = parseFloat(row.querySelector('input[name$="[price]"]').value) || 0;
                const quantity = parseInt(row.querySelector('input[name$="[quantity]"]').value) || 1;
                subtotal += price * quantity; // Subtotal = jumlah harga * kuantitas
            });

            // Perbarui nilai pada input
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('total').value = subtotal.toFixed(2); // Jika ada pajak, tambahkan di sini
        }

        // Event listener untuk checkbox (memilih atau membatalkan pilihan item)
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const itemId = this.value;
                const itemName = this.getAttribute('data-name');
                const itemPrice = this.getAttribute('data-price');
                const tableBody = document.getElementById('selected-items');

                if (this.checked) {
                    // Tambahkan item ke tabel jika dipilih
                    const newRow = `
                    <tr id="row_${itemId}">
                        <td>${itemName}</td>
                        <td>${itemPrice}</td>
                        <td>
                            <input type="number" name="items[${itemId}][quantity]" value="1" min="1" class="form-control quantity-input">
                            <input type="hidden" name="items[${itemId}][price]" value="${itemPrice}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"
                                    data-id="${itemId}">
                                Remove
                            </button>
                        </td>
                    </tr>
                `;
                    tableBody.insertAdjacentHTML('beforeend', newRow);
                } else {
                    // Hapus item dari tabel jika batal dipilih
                    const row = document.getElementById(`row_${itemId}`);
                    if (row) row.remove();
                }

                calculateTotals();
            });
        });

        // Event listener untuk perubahan jumlah (quantity) di tabel
        document.getElementById('selected-items').addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input')) {
                calculateTotals();
            }
        });

        // Event listener untuk tombol remove di tabel
        document.getElementById('selected-items').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-item')) {
                const itemId = e.target.getAttribute('data-id');

                // Uncheck checkbox terkait
                const checkbox = document.getElementById(`item_${itemId}`);
                if (checkbox) checkbox.checked = false;

                // Hapus baris dari tabel
                e.target.closest('tr').remove();
                calculateTotals();
            }
        });
    </script>
@endsection
