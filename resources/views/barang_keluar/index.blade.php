@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Barang Keluar</h1>
        <a href="{{ route('barang_keluar.create') }}" class="btn btn-primary mb-2">Tambah Barang Keluar</a>
        <form action="{{ route('barang_keluar.index') }}" method="GET" class="mb-3">
            <div class="form-row align-items-end">
                <div class="col">
                    <label for="start_date">Tanggal Mulai:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col">
                    <label for="end_date">Tanggal Akhir:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        @if (session('warning'))
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        @if ($barangKeluar->isEmpty())
            <div class="alert alert-warning" role="alert">
                Transaksi tidak ditemukan dalam rentang tanggal yang dipilih.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kode Barang</th>
                        <th>Tanggal</th>
                        <th>Transaksi Keluar</th>
                        <th>Satuan</th>
                        <th>Saldo Akhir</th>
                        <th>Unit</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangKeluar as $item)
                        <tr>
                            <td>{{ ($barangKeluar->currentPage() - 1) * $barangKeluar->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->stokOpname->nama_barang }}</td>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->transaksi_keluar }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->saldo_akhir }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                <a href="{{ route('barang_keluar.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('barang_keluar.destroy', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="d-flex justify-content-center">
            {{ $barangKeluar->links() }}
        </div>
    </div>
@endsection
