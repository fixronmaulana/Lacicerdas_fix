<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard(Request $request)
    {
        $barangMasukPage = $request->query('barangMasukPage', 1);
        $barangKeluarPage = $request->query('barangKeluarPage', 1);

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Mendapatkan transaksi barang masuk hari ini
        $barangMasukToday = BarangMasuk::whereDate('tanggal', $today)->paginate(5, ['*'], 'barangMasukPage', $barangMasukPage);

        // Mendapatkan transaksi barang keluar hari ini
        $barangKeluarToday = BarangKeluar::whereDate('tanggal', $today)->with('stokOpname')->paginate(3, ['*'], 'barangKeluarPage', $barangKeluarPage);

        return view('admin.dashboard', compact('barangMasukToday', 'barangKeluarToday'));
    }
}
