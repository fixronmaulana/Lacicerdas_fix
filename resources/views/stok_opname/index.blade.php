@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h3 mb-3 text-gray-800">Stok Opname</h2>
        </div>
        <div class="col-md-4">
            <form action="{{ route('stok_opname.index') }}" method="GET" class="d-flex justify-content-end">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau satuan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ml-2">Cari</button>
            </form>
        </div>
    </div>

    @if ($stokOpnames->isEmpty())
        <div class="alert alert-warning">Data tidak ditemukan.</div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Stok Opname</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Dokumentasi</th>
                                <th>Nama Barang</th>
                                <th>Kode Barang</th>
                                <th>Saldo Awal</th>
                                <th>Transaksi Masuk</th>
                                <th>Transaksi Keluar</th>
                                <th>Stok Akhir</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokOpnames as $stokOpname)
                                <tr>
                                    <td>{{ ($stokOpnames->currentPage() - 1) * $stokOpnames->perPage() + $loop->iteration }}</td>
                                    <td>{{ $stokOpname->tanggal_dokumen }}</td>
                                    <td>{{ $stokOpname->nama_barang }}</td>
                                    <td>{{ $stokOpname->kode_barang }}</td>
                                    <td>{{ $stokOpname->saldo_awal }}</td>
                                    <td>{{ $stokOpname->transaksi_masuk }}</td>
                                    <td>{{ $stokOpname->transaksi_keluar }}</td>
                                    <td>{{ $stokOpname->stok_akhir }}</td>
                                    <td>{{ $stokOpname->satuan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            {{ $stokOpnames->links() }}
        </div>
    @endif
</div>
@endsection
