<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\StokOpname;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $barangKeluar = BarangKeluar::with('stokOpname');

        if ($start_date) {
            $barangKeluar->whereDate('tanggal', '>=', $start_date);
        }

        if ($end_date) {
            $barangKeluar->whereDate('tanggal', '<=', $end_date);
        }

        $barangKeluar = $barangKeluar->paginate(10);

        if($barangKeluar->isEmpty()) {
            return view('barang_keluar.index', compact('barangKeluar'))
            ->with('warning', 'Transaksi tidak ditemukan dalam rentang tanggal yang dipilih');
        }

        return view('barang_keluar.index', compact('barangKeluar'));
    }

    public function create()
    {
        $stokOpnames = StokOpname::all();
        return view('barang_keluar.create', compact('stokOpnames'));
    }

    public function store(Request $request)
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $request->validate([
            'stok_opname_id' => 'required|exists:stok_opnames,id',
            'tanggal' => ['required', 'date', function ($attribute, $value, $fail) use ($today) {
                $inputDate = Carbon::parse($value)->startOfDay();
                if ($inputDate->lt($today)) {
                    $fail('Tanggal harus hari ini atau setelahnya.');
                }
            }],
            'transaksi_keluar' => 'required|integer|min:1',
            'unit' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ], [
            'transaksi_keluar.min' => 'Masukan jumlah min 1 / yang nilainya positif',
        ]);

        $stokOpname = StokOpname::findOrFail($request->stok_opname_id);

        if ($request->transaksi_keluar > $stokOpname->stok_akhir) {
            return redirect()->back()->withErrors(['transaksi_keluar' => 'Stok tidak mencukupi untuk transaksi keluar ini.']);
        }

        $saldo_akhir = $stokOpname->stok_akhir - $request->transaksi_keluar;

        BarangKeluar::create([
            'stok_opname_id' => $stokOpname->id,
            'kode_barang' => $stokOpname->kode_barang,
            'tanggal' => $request->tanggal,
            'transaksi_keluar' => $request->transaksi_keluar,
            'saldo_akhir' => $saldo_akhir,
            'satuan' => $stokOpname->satuan,
            'unit' => $request->unit,
            'keterangan' => $request->keterangan,
        ]);

        $this->updateStokOpnameKeluar($request->stok_opname_id, $request->transaksi_keluar);

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil ditambahkan.');
    }

    private function updateStokOpnameKeluar($stok_opname_id, $transaksi_keluar)
    {
        $stokOpname = StokOpname::find($stok_opname_id);

        if ($stokOpname) {
            // Hitung stok_akhir dengan mengurangi transaksi keluar baru
            $stokOpname->transaksi_keluar =+ $transaksi_keluar;
            $stokOpname->stok_akhir = $stokOpname->saldo_awal + $stokOpname->transaksi_masuk - $stokOpname->transaksi_keluar;
            $stokOpname->save();
        }
    }

    public function edit(BarangKeluar $barangKeluar)
    {
        $stokOpnames = StokOpname::all();
        return view('barang_keluar.edit', compact('barangKeluar', 'stokOpnames'));
    }

    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $request->validate([
            'stok_opname_id' => 'required|exists:stok_opnames,id',
            'tanggal' => ['required', 'date', function ($attribute, $value, $fail) use ($today) {
                $inputDate = Carbon::parse($value)->startOfDay();
                if ($inputDate->lt($today)) {
                    $fail('Tanggal harus hari ini atau setelahnya.');
                }
            }],
            'transaksi_keluar' => 'required|integer|min:1',
            'unit' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $stokOpname = StokOpname::findOrFail($request->stok_opname_id);
        $perubahanTransaksiKeluar = $request->transaksi_keluar - $barangKeluar->transaksi_keluar;
        $saldo_akhir = $stokOpname->stok_akhir - $perubahanTransaksiKeluar;

        if ($saldo_akhir < 0) {
            return redirect()->back()->withErrors(['transaksi_keluar' => 'Stok tidak mencukupi untuk transaksi keluar ini.']);
        }

        $barangKeluar->update([
            'stok_opname_id' => $stokOpname->id,
            'kode_barang' => $stokOpname->kode_barang,
            'tanggal' => $request->tanggal,
            'transaksi_keluar' => $request->transaksi_keluar,
            'saldo_akhir' => $saldo_akhir,
            'satuan' => $stokOpname->satuan,
            'unit' => $request->unit,
            'keterangan' => $request->keterangan,
        ]);

        $stokOpname->update([
            'stok_akhir' => $saldo_akhir,
        ]);

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $stokOpname = StokOpname::find($barangKeluar->stok_opname_id);

        DB::transaction(function () use ($barangKeluar, $stokOpname) {
            $stokOpname->transaksi_keluar = max(0, $stokOpname->transaksi_keluar - $barangKeluar->transaksi_keluar);
            $stokOpname->stok_akhir = max(0, $stokOpname->stok_akhir + $barangKeluar->transaksi_keluar);

            if ($stokOpname->transaksi_keluar == 0 && $stokOpname->stok_akhir == 0) {
                $stokOpname->saldo_awal = 0;
                $stokOpname->transaksi_masuk = 0;
                $stokOpname->transaksi_keluar = 0;
                $stokOpname->stok_akhir = 0;
            }

            $stokOpname->save();
            $barangKeluar->delete();
        });

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil dihapus.');
    }

}
