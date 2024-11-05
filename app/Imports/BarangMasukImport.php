<?php

namespace App\Imports;

use App\Models\BarangMasuk;
use App\Models\StokOpname;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class BarangMasukImport implements ToModel, WithHeadingRow
{
     // Header yang diharapkan
     protected $expectedHeaders = ['tanggal', 'nama_barang', 'jumlah', 'satuan'];

     public function headingRow(): int
     {
         return 1;
     }

     public function model(array $row)
     {
         // Log untuk memeriksa apakah header dan row terbaca dengan benar
         Log::info('Row yang diimport: ' . json_encode($row));

         // Validasi apakah kolom nama_barang, jumlah, satuan, dan tanggal terisi
         if (empty($row['nama_barang']) || empty($row['jumlah']) || empty($row['satuan']) || empty($row['tanggal'])) {
             Log::warning('Data tidak valid atau kolom kosong: ' . json_encode($row));
             throw ValidationException::withMessages([
                 'error' => 'Ada data yang kosong atau tidak sesuai pada baris: ' . json_encode($row)
             ]);
         }

          // Memulai transaksi database
    DB::beginTransaction();
    try {
        // Cek transaksi terakhir untuk barang dan satuan yang sama di BarangMasuk
        $lastTransaction = BarangMasuk::where('nama_barang', $row['nama_barang'])
            ->where('satuan', $row['satuan'])
            ->orderBy('tanggal', 'desc')
            ->first();

        // Set saldo_awal berdasarkan saldo_akhir dari transaksi terakhir atau 0 jika tidak ada transaksi sebelumnya
        $saldo_awal = $lastTransaction ? $lastTransaction->saldo_akhir : 0;

        // Cek apakah stok opname untuk nama_barang dan satuan sudah ada
        $stokOpname = StokOpname::where('nama_barang', $row['nama_barang'])
            ->where('satuan', $row['satuan'])
            ->first();

        // If StokOpname exists, update it, otherwise create a new one
        if ($stokOpname) {
            $saldo_awal = $stokOpname->stok_akhir;
            $stokOpname->transaksi_masuk = $row['jumlah'];
            $stokOpname->stok_akhir = $saldo_awal + $row['jumlah'];  // Update stok_akhir based on saldo_awal

        } else {
            // If no StokOpname exists, create a new one with the provided data
            $saldo_awal = 0;
            $stokOpname = StokOpname::create([
                'nama_barang' => $row['nama_barang'],
                'kode_barang' => $this->generateUniqueKodeBarang(),
                'satuan' => $row['satuan'],
                'saldo_awal' => $saldo_awal,
                'transaksi_masuk' => $row['jumlah'],
                'stok_akhir' => $saldo_awal + $row['jumlah'],  // Initialize stok_akhir
            ]);
        }

        // Now insert into BarangMasuk with the calculated saldo_awal and stok_akhir
        $barangMasuk = BarangMasuk::create([
            'nama_barang' => $row['nama_barang'],
            'kode_barang' => $stokOpname->kode_barang,
            'satuan' => $row['satuan'],
            'tanggal' => $row['tanggal'] ?? now(),  // Default to today if not provided
            'transaksi_masuk' => $row['jumlah'],
            'saldo_awal' => $saldo_awal,
            'saldo_akhir' => $stokOpname->stok_akhir,  // Use the calculated stok_akhir from StokOpname
            'keterangan' => $row['keterangan'] ?? null,
        ]);

        $stokOpname->saldo_awal = $saldo_awal; // Saldo awal untuk stok opname
        $stokOpname->stok_akhir = $saldo_awal + $row['jumlah'];
        $stokOpname->save();

        DB::commit();
         } catch (\Exception $e) {
             DB::rollBack();
             Log::error('Gagal mengimpor data: ' . $e->getMessage());
             throw ValidationException::withMessages([
                 'error' => 'Gagal mengimpor data pada baris: ' . json_encode($row)
             ]);
         }
     }

     // Fungsi untuk validasi header dengan case-insensitive
     public function validateHeaders(array $actualHeaders)
     {
         $lowerExpectedHeaders = array_map('strtolower', $this->expectedHeaders);
         $lowerActualHeaders = array_map('strtolower', $actualHeaders);

         $diff = array_diff($lowerExpectedHeaders, $lowerActualHeaders);
         if (!empty($diff)) {
             Log::warning('Header tidak sesuai: ' . json_encode($actualHeaders));
             throw ValidationException::withMessages([
                 'error' => 'Header tidak sesuai. Harap gunakan header yang benar: ' . implode(', ', $this->expectedHeaders)
             ]);
         }
     }

    private function generateUniqueKodeBarang()
    {
        // Generate a unique kode_barang based on the maximum value in StokOpname
        $maxKode = StokOpname::max('kode_barang');
        return $maxKode + 1;
    }
}

