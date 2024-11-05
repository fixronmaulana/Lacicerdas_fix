@extends('layouts.base')

@section('content')

<div class="container-fluid">

    <h1>History Barang</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <form action="{{ route('admin.histori_barang.index') }}" method="GET" class="d-flex justify-content-end">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode barang..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ml-2">Cari</button>
            </form>
        </div>
    </div>

    <!-- Tabel Hasil Pencarian -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><br> </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            {{-- <th>Tanggal</th>
                            <th>Keterangan</th> --}}
                            <th>Nama Barang</th>
                            <th>Kode Barang</th>
                            <th>Satuan</th>
                            <th>Lihat Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stok_opname as $item)
                            <tr>
                                <td>{{ ($stok_opname->currentPage() - 1) * $stok_opname->perPage() + $loop->iteration }}</td>
                                {{-- <td>{{ $loop->iteration }}</td> --}}
                                {{-- <td>{{ $item->tanggal_dokumen }}</td> --}}
                                {{-- <td>{{ $item->keterangan }}</td> --}}
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>
                                    <a href="{{ route('admin.histori_barang.show', $item->id) }}" class="btn btn-primary">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $stok_opname->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
