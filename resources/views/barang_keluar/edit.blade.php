@extends('layouts.base')

@section('content')
<div class="container">
    <h1>Edit Barang Keluar</h1>
    <form action="{{ route('barang_keluar.update', $barangKeluar->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama Barang -->
        <div class="form-group">
            <label for="stok_opname_id">Nama Barang:</label>
            <select name="stok_opname_id" id="stok_opname_id" class="form-control @error('stok_opname_id') is-invalid @enderror">
                @foreach($stokOpnames as $stok)
                    <option value="{{ $stok->id }}" {{ $stok->id == old('stok_opname_id', $barangKeluar->stok_opname_id) ? 'selected' : '' }}>
                        {{ $stok->nama_barang }} ({{ $stok->kode_barang }})
                    </option>
                @endforeach
            </select>
            @error('stok_opname_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tanggal -->
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                   value="{{ old('tanggal', $barangKeluar->tanggal) }}" required>
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Transaksi Keluar -->
        <div class="form-group">
            <label for="transaksi_keluar">Transaksi Keluar:</label>
            <input type="number" name="transaksi_keluar" id="transaksi_keluar" class="form-control @error('transaksi_keluar') is-invalid @enderror"
                   value="{{ old('transaksi_keluar', $barangKeluar->transaksi_keluar) }}" required>
            @error('transaksi_keluar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Unit -->
        <div class="form-group">
            <label for="unit">Unit:</label>
            <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror"
                   value="{{ old('unit', $barangKeluar->unit) }}">
            @error('unit')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Keterangan -->
        <div class="form-group">
            <label for="keterangan">Keterangan:</label>
            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $barangKeluar->keterangan) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
