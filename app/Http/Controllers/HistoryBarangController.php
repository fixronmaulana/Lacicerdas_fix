<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\StokOpname;
use Illuminate\Http\Request;

class HistoryBarangController extends Controller
{
    // Menampilkan daftar stok opname dengan pencarian
    public function index(Request $request)
    {
        // Menangani pencarian barang berdasarkan nama atau kode barang
        $search = $request->input('search');

        // Ambil data dari model StokOpname dengan fitur pencarian
        $stok_opname = StokOpname::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama_barang', 'like', '%' . $search . '%') // Pencarian nama barang
                    ->orWhere('kode_barang', 'like', '%' . $search . '%'); // Pencarian kode barang
            })
            ->paginate(5); // Pagination 5 item per halaman

        // Return view dengan data stok opname
        return view('history_barang.index', compact('stok_opname', 'search'));
    }

    // Menampilkan detail transaksi barang masuk dan keluar untuk barang tertentu
    public function show($id)
    {
        // Ambil data stok opname berdasarkan id (kode_barang)
        $stokOpname = StokOpname::where('kode_barang', $id)->firstOrFail();

        // Ambil barang masuk dan keluar berdasarkan nama_barang
        $barangMasuk = BarangMasuk::where('nama_barang', $stokOpname->nama_barang)
            ->orderBy('tanggal', 'asc')
            ->paginate(5);

        $barangKeluar = BarangKeluar::where('kode_barang', $stokOpname->kode_barang)
            ->orderBy('tanggal', 'asc')
            ->paginate(5);

        // Menggabungkan semua transaksi
        $allTransactions = collect([]);

        // Gabungkan barang masuk
        foreach ($barangMasuk as $masuk) {
            $allTransactions->push([
                'tanggal' => $masuk->tanggal,
                'keterangan' => 'Masuk',
                'masuk' => $masuk->transaksi_masuk,
                'keluar' => 0,
                'unit' => '', // Sesuaikan jika unit tidak ada
            ]);
        }

        // Gabungkan barang keluar
        foreach ($barangKeluar as $keluar) {
            $allTransactions->push([
                'tanggal' => $keluar->tanggal,
                'keterangan' => $keluar->keterangan,
                'masuk' => 0,
                'keluar' => $keluar->transaksi_keluar,
                'unit' => $keluar->unit,
            ]);
        }

        // Urutkan berdasarkan tanggal dan keterangan
        $allTransactions = $allTransactions->sortBy(function ($transaction) {
            return [$transaction['tanggal'], $transaction['keterangan']]; // Urutkan berdasarkan tanggal dan keterangan
        });

        // Passing data ke view
        return view('history_barang.detail', [
            'item' => $stokOpname,
            'barangMasuk' => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'allTransactions' => $allTransactions, // Passing allTransactions ke view
        ]);
    }
}
