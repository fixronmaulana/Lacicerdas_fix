<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangKeluarTable extends Migration
{
    public function up()
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_opname_id')->constrained()->onDelete('cascade');
            $table->string('kode_barang');
            $table->date('tanggal');
            $table->integer('transaksi_keluar');
            $table->integer('saldo_akhir');
            $table->string('satuan');
            $table->string('unit');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_keluar');
    }
}

