<?php

namespace App\Http\Controllers;

use App\Exports\BarangMasukExport;
use App\Exports\BarangKeluarExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $format = $request->input('format');

        // Validasi rentang tanggal
        if (strtotime($startDate) > strtotime($endDate)) {
            return redirect()->back()->with('error', 'Laporan tidak dapat diunduh. Silakan masukkan rentang tanggal yang sesuai.');
        }

        if ($format === 'excel') {
            return Excel::download(new BarangMasukExport($startDate, $endDate), 'barang_masuk.xlsx');
        } elseif ($format === 'pdf') {
            // Ambil data barang masuk berdasarkan rentang tanggal
            $barangMasuk = BarangMasuk::whereBetween('tanggal', [$startDate, $endDate])->get();

            // Menggunakan view yang sudah dibuat untuk PDF
            $pdf = PDF::loadView('laporan.pdf_barang_masuk', compact('barangMasuk', 'startDate', 'endDate'));

            // Unduh PDF dengan nama 'barang_masuk.pdf'
            return $pdf->download('barang_masuk.pdf');
        } else {
            return redirect()->back()->with('error', 'Format laporan tidak valid.');
        }
    }


    public function exportBarangKeluar(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $format = $request->input('format');

        // Validasi rentang tanggal
        if (strtotime($startDate) > strtotime($endDate)) {
            return redirect()->back()->with('error', 'Laporan tidak dapat diunduh. Silakan masukkan rentang tanggal yang sesuai.');
        }

        if ($format === 'excel') {
            return Excel::download(new BarangKeluarExport($startDate, $endDate), 'barang_keluar.xlsx');
        } elseif ($format === 'pdf') {
            // Ambil data barang masuk berdasarkan rentang tanggal
            $barangKeluar = BarangKeluar::whereBetween('tanggal', [$startDate, $endDate])->get();

            // Menggunakan view yang sudah dibuat untuk PDF
            $pdf = PDF::loadView('laporan.pdf_barang_keluar', compact('barangKeluar', 'startDate', 'endDate'));

            // Unduh PDF dengan nama 'barang_masuk.pdf'
            return $pdf->download('barang_keluar.pdf');
        } else {
            return redirect()->back()->with('error', 'Format laporan tidak valid.');
        }
    }
}
