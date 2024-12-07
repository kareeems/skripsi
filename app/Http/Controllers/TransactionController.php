<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\TransactionItem;


class TransactionController extends Controller
{
    // Menampilkan semua transaksi
    public function index()
    {
        $transactions = Transaction::with('user')->get();
        return view('transactions.index', compact('transactions'));
    }

    // Form untuk membuat transaksi baru
    public function create()
    {
        $users = User::where('role', 'student')->get(); // Ambil semua pengguna
        $selectedItems = []; // Kosongkan jika transaksi belum ada

        // Ambil semua item yang belum dipilih
        $unselectedItems = Item::whereNotIn('id', function ($query) {
            $query->select('item_id')->from('transaction_items');
        })->get();

        return view('transactions.create', compact('users', 'selectedItems', 'unselectedItems'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
        ]);

        Transaction::create($request->all());

        // Simpan data transaction_items
        foreach ($request->items as $itemId => $itemData) {
            if (isset($itemData['selected'])) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $itemId,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    // Menampilkan detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    // Form untuk mengedit transaksi
    public function edit($id)
    {
        $users = User::where('role', 'student')->get(); // Ambil semua pengguna
        $transaction = Transaction::findOrFail($id);
        return view('transactions.edit', compact('transaction','users'));
    }

    // Mengupdate transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    // Menghapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
