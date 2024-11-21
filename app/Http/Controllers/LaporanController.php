<?php

namespace App\Http\Controllers;

use App\Exports\BarangMasukExport;
use App\Exports\BarangKeluarExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function viewBarangMasuk()
    {
        return view('laporan.barang_masuk');
    }

    public function viewBarangKeluar()
    {
        return view('laporan.barang_keluar');
    }

    public function exportBarangMasuk(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validasi rentang tanggal
        if (strtotime($startDate) > strtotime($endDate)) {
            return redirect()->back()->with('error', 'Laporan tidak dapat diunduh. Silakan masukkan rentang tanggal yang sesuai.');
        }

        return Excel::download(new BarangMasukExport($startDate, $endDate), 'barang_masuk.xlsx');
    }

    public function exportBarangKeluar(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validasi rentang tanggal
        if (strtotime($startDate) > strtotime($endDate)) {
            return redirect()->back()->with('error', 'Laporan tidak dapat diunduh. Silakan masukkan rentang tanggal yang sesuai.');
        }

        return Excel::download(new BarangKeluarExport($startDate, $endDate), 'barang_keluar.xlsx');
    }
}
