<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 'stok_opnames';
    protected $fillable = [
        'tanggal_dokumen', 'nama_barang', 'kode_barang', 'saldo_awal', 'transaksi_masuk', 'transaksi_keluar', 'stok_akhir', 'satuan',
    ];

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Generate a unique code for the item.
     *
     * @return int
     */
    public static function generateKodeBarang()
    {
        $maxKode = self::max('kode_barang');
        return $maxKode ? $maxKode + 1 : 1; // Jika tidak ada kode, mulai dari 1
    }
}


