@extends('layouts.base')

@section('content')
<div class="container">
    <h2>Tambah Barang Masuk</h2>
    <form action="{{ route('barang_masuk.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}">
            @error('nama_barang')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="kode_barang">Kode Barang</label>
            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" readonly>
            @error('kode_barang')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="satuan">Satuan</label>
            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" placeholder="satuan: pcs, pack, rim" value="{{ old('satuan') }}">
            @error('satuan')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal') }}">
            @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="transaksi_masuk">Transaksi Masuk</label>
            <input type="number" class="form-control @error('transaksi_masuk') is-invalid @enderror" id="transaksi_masuk" name="transaksi_masuk" value="{{ old('transaksi_masuk') }}" min="1">
            @error('transaksi_masuk')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
            @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{route('barang_masuk.index')}}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
document.getElementById('nama_barang').addEventListener('input', function() {
    var namaBarang = this.value;
    var kodeBarangInput = document.getElementById('kode_barang');

    if (!kodeBarangInput.value) {
        var maxKodeBarang = {{ $maxKodeBarang ?? 0 }};
        kodeBarangInput.value = maxKodeBarang + 1;
    }
});
</script>
@endsection
