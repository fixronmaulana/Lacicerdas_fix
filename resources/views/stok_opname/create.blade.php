<!-- resources/views/stok_opname/create.blade.php -->
@extends('layouts.base')

@section('content')
<div class="container">
    <h1>Tambah Stok Opname</h1>
    <form action="{{ route('stok_opname.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
        </div>
        <div class="form-group">
            <label for="kode_barang">Kode Barang</label>
            <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
        </div>
        <div class="form-group">
            <label for="saldo_awal">Saldo Awal</label>
            <input type="number" class="form-control" id="saldo_awal" name="saldo_awal" required>
        </div>
        <div class="form-group">
            <label for="satuan">Satuan</label>
            <input type="text" class="form-control" id="satuan" name="satuan" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="{{ route('stok_opname.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
