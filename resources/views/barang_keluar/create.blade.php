@extends('layouts.base')

@section('content')
<div class="container">
    <h2>Tambah Barang Keluar</h2>
    <form action="{{ route('barang_keluar.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="stok_opname_id">Barang</label>
            <select class="form-control @error('stok_opname_id') is-invalid @enderror" id="stok_opname_id" name="stok_opname_id">
                <option value="">Pilih Barang</option>
                @foreach($stokOpnames as $stokOpname)
                    <option value="{{ $stokOpname->id }}" data-kode="{{ $stokOpname->kode_barang }}" data-satuan="{{ $stokOpname->satuan }}" {{ old('stok_opname_id') == $stokOpname->id ? 'selected' : '' }}>
                        {{ $stokOpname->nama_barang }}
                    </option>
                @endforeach
            </select>
            @error('stok_opname_id')
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
            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan') }}" readonly>
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
            <label for="transaksi_keluar">Transaksi Keluar</label>
            <input type="number" class="form-control @error('transaksi_keluar') is-invalid @enderror" id="transaksi_keluar" name="transaksi_keluar" value="{{ old('transaksi_keluar') }}" min="1">
            @error('transaksi_keluar')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="unit">Unit</label>
            <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit') }}">
            @error('unit')
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
        <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
document.getElementById('stok_opname_id').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var kodeBarang = selectedOption.getAttribute('data-kode');
    var satuan = selectedOption.getAttribute('data-satuan');

    document.getElementById('kode_barang').value = kodeBarang;
    document.getElementById('satuan').value = satuan;
});
</script>
@endsection
