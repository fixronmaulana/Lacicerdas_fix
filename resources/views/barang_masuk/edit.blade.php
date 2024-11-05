@extends('layouts.base')

@section('content')
<div class="container">
    <h2>Edit Barang Masuk</h2>
    <form action="{{ route('barang_masuk.update', $barangMasuk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" value="{{ $barangMasuk->nama_barang }}" readonly>
        </div>

        <div class="form-group">
            <label for="kode_barang">Kode Barang</label>
            <input type="text" class="form-control" id="kode_barang" value="{{ $barangMasuk->kode_barang }}" readonly>
        </div>

        <div class="form-group">
            <label for="satuan">Satuan</label>
            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', $barangMasuk->satuan) }}" required>
            @error('satuan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $barangMasuk->tanggal) }}" required>
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="transaksi_masuk">Transaksi Masuk</label>
            <input type="number" class="form-control @error('transaksi_masuk') is-invalid @enderror" id="transaksi_masuk" name="transaksi_masuk" value="{{ old('transaksi_masuk', $barangMasuk->transaksi_masuk) }}" min="1" required>
            @error('transaksi_masuk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan', $barangMasuk->keterangan) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
