<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';
    protected $fillable = [
        'stok_opname_id',
        'kode_barang',
        'tanggal',
        'transaksi_keluar',
        'saldo_akhir',
        'satuan',
        'unit',
        'keterangan',
    ];

    public function stokOpname()
    {
        return $this->belongsTo(StokOpname::class);
    }
}
