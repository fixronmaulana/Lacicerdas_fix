<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokOpnamesTable extends Migration
{
        public function up()
    {
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_dokumen')->default(now());
            $table->string('nama_barang');
            $table->string('kode_barang')->nullable();
            $table->integer('saldo_awal');
            $table->integer('transaksi_masuk')->default(0);
            $table->integer('transaksi_keluar')->default(0);
            $table->integer('stok_akhir');
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_opnames');
    }
}

