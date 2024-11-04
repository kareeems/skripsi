<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan daftar item transaksi
    public function index()
    {
        $items = Item::paginate(20);
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
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:pondok,sekolah',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Transaction item created successfully.');
    }

    public function edit(Item $item)
    {
        return view('items.create', compact('item')); // Tampilkan form edit
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:pondok,sekolah',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
