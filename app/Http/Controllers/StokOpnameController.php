<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokOpname;
use App\Models\BarangKeluar;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StokOpname::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_barang', 'like', '%' . $search . '%')
                ->orWhere('satuan', 'like', '%' . $search . '%');
        }

        $stokOpnames = $query->paginate(5);

        // Menghitung transaksi_keluar dan stok_akhir
        foreach ($stokOpnames as $stokOpname) {
            $stokOpname->transaksi_keluar = BarangKeluar::where('stok_opname_id', $stokOpname->id)->sum('transaksi_keluar');

            // Hitung stok_akhir berdasarkan saldo_awal dan transaksi_masuk dan transaksi_keluar
            // $stokOpname->stok_akhir = $stokOpname->saldo_awal + $stokOpname->transaksi_masuk - $stokOpname->transaksi_keluar; //ini harus diHAPUS!
        }

        return view('stok_opname.index', compact('stokOpnames'));
    }

    public function create()
    {
        return view('stok_opname.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_dokumen' => 'required|date',
            'nama_barang' => 'required|string',
            'kode_barang' => 'required|string',
            'saldo_awal' => 'required|integer',
            'satuan' => 'required|string',
        ]);

        StokOpname::create($request->all());

        return redirect()->route('stok_opname.index')->with('success', 'Stok opname berhasil ditambahkan.');
    }

    public function edit(StokOpname $stokOpname)
    {
        return view('stok_opname.edit', compact('stokOpname'));
    }

    public function update(Request $request, StokOpname $stokOpname)
    {
        $request->validate([
            'tanggal_dokumen' => 'required|date',
            'nama_barang' => 'required|string',
            'kode_barang' => 'required|string',
            'saldo_awal' => 'required|integer',
            'satuan' => 'required|string',
        ]);

        $stokOpname->update($request->all());

        return redirect()->route('stok_opname.index')->with('success', 'Stok opname berhasil diperbarui.');
    }

    public function destroy(StokOpname $stokOpname)
    {
        $stokOpname->delete();
        return redirect()->route('stok_opname.index')->with('success', 'Stok opname berhasil dihapus.');
    }

    public function updateStokOpname($nama_barang, $satuan, $jumlah, $isKeluar = false)
{
    $stokOpname = StokOpname::where('nama_barang', $nama_barang)
        ->where('satuan', $satuan)
        ->first();

    if ($stokOpname) {
        $saldo_awal = $stokOpname->saldo_awal;

        if ($isKeluar) {
            $stokOpname->transaksi_keluar = max(0, $stokOpname->transaksi_keluar - $jumlah);
        } else {
            $stokOpname->transaksi_masuk += $jumlah;
        }

        // Update stok_akhir calculation
        $stokOpname->stok_akhir = $saldo_awal + $stokOpname->transaksi_masuk - $stokOpname->transaksi_keluar;
        $stokOpname->save();
    }
}



}
