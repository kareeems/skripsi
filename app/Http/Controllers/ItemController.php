<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan daftar item transaksi
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // Menampilkan form untuk menambahkan item transaksi
    public function create()
    {
        return view('items.create');
    }

    // Menyimpan item transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:pondok,sekolah',
        ]);

        TransactionItem::create($request->all());
        return redirect()->route('items.index')->with('success', 'Transaction item created successfully.');
    }
}
