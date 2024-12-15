<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dari request, default ke tahun saat ini
        $year = $request->input('year', now()->year);

        // Ringkasan data
        $userCount = User::count();
        $itemCount = Item::count();
        $transactionCount = Transaction::count();

        // Data pemasukan per bulan
        $revenueData = Transaction::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Isi semua bulan kosong dengan nilai 0
        $fullData = collect(range(1, 12))->mapWithKeys(function ($month) use ($revenueData) {
            return [$month => $revenueData->get($month, 0)];
        });

        // Daftar tahun untuk dropdown
        $availableYears = Transaction::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.index2', [
            'userCount' => $userCount,
            'itemCount' => $itemCount,
            'transactionCount' => $transactionCount,
            'revenueData' => $fullData,
            'availableYears' => $availableYears,
            'currentYear' => $year,
        ]);
    }
}
