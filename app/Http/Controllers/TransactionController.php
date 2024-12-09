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
        $transactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')->get();

        return view('transactions.index', compact('transactions'));
    }

    // Form untuk membuat transaksi baru
    public function create()
    {
        $users = User::where('role', 'student')->get(); // Ambil semua pengguna
        $items = Item::all(); // Kosongkan jika transaksi belum ada

        return view('transactions.create', compact('users', 'items'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::create($request->all());

        // Simpan data transaction_items
        foreach ($request->items as $itemId => $itemData) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'item_id' => $itemId,
                'quantity' => $itemData['quantity'],
                'price' => doubleval($itemData['price']),
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    // Menampilkan detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'items'])->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    // Form untuk mengedit transaksi
    public function edit($id)
    {
        $users = User::where('role', 'student')->get(); // Ambil semua pengguna
        $transaction = Transaction::findOrFail($id);
        $items = Item::all();
        // Ambil item yang sudah dipilih pada transaksi
        $selectedItems = $transaction->items->mapWithKeys(function ($item) {
            return [
                $item->id => [
                    'name' => $item->name,
                    'type' => $item->type,
                    'price' => $item->pivot->price,  // Ambil price dari pivot
                    'quantity' => $item->pivot->quantity,  // Ambil quantity dari pivot
                ]
            ];
        });

        return view('transactions.edit', compact('transaction', 'users', 'items', 'selectedItems'));
    }

    // Mengupdate transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Ambil data transaction by ID
        $transaction = Transaction::findOrFail($id);

        // Ambil data item yang dikirimkan
        $submittedItemIds = array_keys($request->items);

        // Ambil semua item yang saat ini terkait dengan transaksi
        $currentItemIds = $transaction->items->pluck('id')->toArray();

        // Cari item yang dihapus (item yang ada di database tetapi tidak ada di form)
        $itemsToDelete = array_diff($currentItemIds, $submittedItemIds);

        // Hapus item yang tidak ada di form (menghapus relasi di pivot table)
        if ($itemsToDelete) {
            $transaction->items()->detach($itemsToDelete);
        }

        // Update data transaction
        $transaction->update($request->all());

        // Perbarui item yang ada dalam transaksi
        foreach ($request->items as $itemId => $itemData) {
            // Jika item ada dalam transaksi, update pivot-nya
            if (in_array($itemId, $submittedItemIds)) {
                $transaction->items()->updateExistingPivot($itemId, [
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);
            }
        }

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
