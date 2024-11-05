@extends('layouts.base')

@section('content')
<div class="container">
    <h1>Barang Masuk</h1>
    <a href="{{ route('barang_masuk.create') }}" class="btn btn-primary mb-2">Tambah Barang Masuk</a>

    <!-- Form Filter -->
    <form action="{{ route('barang_masuk.index') }}" method="GET" class="mb-2">
        <div class="form-row align-items-end">
            <div class="col">
                <label for="start_date">Tanggal Mulai:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col">
                <label for="end_date">Tanggal Akhir:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Form Import -->
    <form action="{{ route('barang_masuk.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Upload File Excel</label>
            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" id="file" required>
            @error('file')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mb-3">Import</button>
    </form>

    <!-- Pesan Notifikasi -->
    @if (session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


    <!-- Tabel Data -->
    @if($barangMasuk->isEmpty())
        <div class="alert alert-warning" role="alert">
            Transaksi tidak ditemukan dalam rentang tanggal yang dipilih.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Tanggal</th>
                    <th>Transaksi Masuk</th>
                    <th>Saldo Akhir</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangMasuk as $item)
                    <tr>
                        <td>{{ ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage() + $loop->iteration }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->transaksi_masuk }}</td>
                        <td>{{ $item->saldo_akhir }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <a href="{{ route('barang_masuk.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('barang_masuk.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $barangMasuk->links() }}
    </div>
</div>
@endsection
