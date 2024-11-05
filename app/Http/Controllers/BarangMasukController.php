<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\StokOpname;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangMasukImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        // Mulai query untuk mengambil data barang masuk
        $barangMasuk = BarangMasuk::query();

        // Tambahkan filter tanggal jika ada
        if ($start_date) {
            $barangMasuk->whereDate('tanggal', '>=', $start_date);
        }

        if ($end_date) {
            $barangMasuk->whereDate('tanggal', '<=', $end_date);
        }

        // Lakukan paginasi untuk hasil query
        $barangMasuk = $barangMasuk->paginate(5);

        // Cek jika tidak ada transaksi
        if ($barangMasuk->isEmpty()) {
            return view('barang_masuk.index', compact('barangMasuk'))
                ->with('warning', 'Transaksi tidak ditemukan dalam rentang tanggal yang dipilih.');
        }

        return view('barang_masuk.index', compact('barangMasuk'));
    }

    public function create()
    {
        $maxKodeBarang = StokOpname::max('kode_barang');
        return view('barang_masuk.create', compact('maxKodeBarang'));
    }

    public function store(Request $request)
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $request->validate([
            'nama_barang' => 'required|string',
            'kode_barang' => 'required|integer',
            'satuan' => 'required|string',
            'tanggal' => ['required', 'date', function ($attribute, $value, $fail) use ($today) {
                $inputDate = Carbon::parse($value)->startOfDay();
                if ($inputDate->lt($today)) {
                    $fail('Tanggal harus hari ini atau setelahnya.');
                }
            }],
            'transaksi_masuk' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'transaksi_masuk.min' => 'Transaksi minimal angka 1 / bernilai positif',
        ]);

        // Ambil data stok opname berdasarkan nama_barang dan satuan
        $stokOpname = StokOpname::where('nama_barang', $request->nama_barang)
            ->where('satuan', $request->satuan)
            ->first();

        // Menghitung saldo awal dan stok akhir
        if ($stokOpname) {
            // Jika stok opname sudah ada, ambil saldo akhir
            $saldo_awal = $stokOpname->stok_akhir; // Saldo akhir sebelumnya
            $stokOpname->transaksi_masuk = $request->transaksi_masuk; // Update transaksi masuk
            $stokOpname->stok_akhir = $saldo_awal + $stokOpname->transaksi_masuk; // Hitung stok akhir baru
        } else {
            // Jika stok opname belum ada, buat stok opname baru
            $saldo_awal = 0; // Jika baru, saldo awal dari 0
            $stokOpname = StokOpname::create([
                'nama_barang' => $request->nama_barang,
                'kode_barang' => $request->kode_barang,
                'satuan' => $request->satuan,
                'saldo_awal' => $saldo_awal,
                'transaksi_masuk' => $request->transaksi_masuk,
                'stok_akhir' => $request->transaksi_masuk,
            ]);
        }

        // Simpan transaksi barang masuk
        $barangMasuk = BarangMasuk::create([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'satuan' => $request->satuan,
            'tanggal' => $request->tanggal,
            'transaksi_masuk' => $request->transaksi_masuk,
            'saldo_awal' => $saldo_awal,
            'saldo_akhir' => $stokOpname->stok_akhir,
            'keterangan' => $request->keterangan,
        ]);

        // Update stok opname setelah barang masuk disimpan
        $stokOpname->saldo_awal = $saldo_awal; // Saldo awal untuk stok opname
        $stokOpname->stok_akhir = $saldo_awal + $stokOpname->transaksi_masuk; // Hitung stok akhir
        $stokOpname->save();

        return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $stokOpnames = StokOpname::all();
        return view('barang_masuk.edit', compact('barangMasuk', 'stokOpnames'));
    }

    public function update(Request $request, $id)
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $validatedData = $request->validate([
            'tanggal' => ['required', 'date', function ($attribute, $value, $fail) use ($today) {
                $inputDate = Carbon::parse($value)->startOfDay();
                if ($inputDate->lt($today)) {
                    $fail('Tanggal harus hari ini atau setelahnya.');
                }
            }],
            'transaksi_masuk' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'transaksi_masuk.min' => 'Transaksi minimal angka 1 / bernilai positif',
        ]);

        try {
            DB::beginTransaction();

            // Ambil data barang masuk yang akan diupdate
            $barangMasuk = BarangMasuk::findOrFail($id);
            $namaBarang = $barangMasuk->nama_barang;
            $satuan = $barangMasuk->satuan;

            // Ambil stok opname yang sesuai dengan nama_barang dan satuan
            $stokOpname = StokOpname::where('nama_barang', $namaBarang)
                ->where('satuan', $satuan)
                ->first();

            // Hitung selisih transaksi masuk
            $selisihTransaksi = $validatedData['transaksi_masuk'] - $barangMasuk->transaksi_masuk;

            // Update saldo awal
            $previousBarangMasuk = BarangMasuk::where('nama_barang', $namaBarang)
                ->where('satuan', $satuan)
                ->where('id', '<', $barangMasuk->id)
                ->orderBy('id', 'desc')
                ->first();

            $saldo_awal = $previousBarangMasuk ? $previousBarangMasuk->saldo_akhir : 0;

            // Update transaksi masuk dan saldo_akhir di tabel barang masuk
            $barangMasuk->update([
                'tanggal' => $validatedData['tanggal'],
                'transaksi_masuk' => $validatedData['transaksi_masuk'],
                'saldo_awal' => $saldo_awal,
                'saldo_akhir' => $saldo_awal + $validatedData['transaksi_masuk'], // Hitung saldo akhir baru
                'keterangan' => $validatedData['keterangan'],
            ]);

            // Jika stokOpname ditemukan
            if ($stokOpname) {
                // Update transaksi masuk dan stok akhir di stok opname
                $stokOpname->transaksi_masuk += $selisihTransaksi;
                $stokOpname->stok_akhir = $saldo_awal + $stokOpname->transaksi_masuk; // Hitung stok akhir baru

                // Simpan stok opname yang diperbarui
                $stokOpname->save();
            }

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        // Ambil data barang masuk yang akan dihapus
        $barangMasuk = BarangMasuk::findOrFail($id);

        // Ambil data stok opname berdasarkan nama_barang dan satuan
        $stokOpname = StokOpname::where('nama_barang', $barangMasuk->nama_barang)
            ->where('satuan', $barangMasuk->satuan)
            ->first();

        // Mulai transaksi database
        try {
            DB::beginTransaction();

            if ($stokOpname) {
                // Kurangi transaksi masuk dari stok opname
                $stokOpname->transaksi_masuk -= $barangMasuk->transaksi_masuk; // Kurangi transaksi masuk
                $stokOpname->stok_akhir -= $barangMasuk->transaksi_masuk; // Sesuaikan stok akhir

                // Jika transaksi masuk menjadi negatif, atur kembali ke 0
                if ($stokOpname->transaksi_masuk < 0) {
                    $stokOpname->transaksi_masuk = 0;
                }
                if ($stokOpname->stok_akhir < 0) {
                    $stokOpname->stok_akhir = 0; // Pastikan stok akhir tidak negatif
                }

                // Jika transaksi masuk dan stok akhir menjadi nol, set semua nilai di stok opname ke nol
                if ($stokOpname->transaksi_masuk == 0 && $stokOpname->stok_akhir == 0) {
                    $stokOpname->saldo_awal = 0;
                    $stokOpname->transaksi_masuk = 0;
                    $stokOpname->stok_akhir = 0;
                }

                $stokOpname->save(); // Simpan perubahan ke stok opname
            }

            // Hapus data barang masuk
            $barangMasuk->delete();

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ], [
            'file.mimes' => 'File harus dalam format Excel (.xlsx, .csv, .xls)',
        ]);

        try {
            // Load file menggunakan PhpSpreadsheet untuk membaca header
            $file = $request->file('file');

            // Log the path of the uploaded file
            Log::info('File uploaded: ' . $file->getPathname());

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $headers = $sheet->toArray()[0]; // Ambil baris pertama (header)

            // Log header yang terbaca
            Log::info('Header dari file Excel: ' . json_encode($headers));

            // Validasi apakah header sesuai
            $import = new BarangMasukImport();
            $import->validateHeaders($headers);

            // Jika header valid, lakukan proses import
            Excel::import($import, $file);

            // Redirect dengan pesan sukses jika berhasil
            return redirect()->route('barang_masuk.index')->with('success', 'Data berhasil diimpor!');
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            Log::error('Gagal membaca file Excel: ' . $e->getMessage());
            return redirect()->route('barang_masuk.index')->with('warning', 'Gagal membaca file Excel. Pastikan file dalam format yang benar.');
        } catch (\Exception $e) {
            Log::error('Gagal mengimpor data: ' . $e->getMessage());
            return redirect()->route('barang_masuk.index')->with('warning', $e->getMessage());
        }
    }
}
