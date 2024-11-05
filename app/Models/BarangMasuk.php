<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $fillable = [
        'nama_barang',
        'satuan',
        'tanggal',
        'transaksi_masuk',
        'saldo_akhir',
        'keterangan',
    ];

    //ini ya
    public function stokOpname()
    {
        return $this->belongsTo(StokOpname::class);
    }

}





