@extends('layouts.base')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="h3 mb-3 text-gray-800">Laporan Barang Masuk</h2>

                {{-- Tampilkan pesan error jika ada --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('laporan.exportBarangMasuk') }}" method="GET">
                    <div class="form-row align-items-end">
                        <div class="col">
                            <label for="start_date">Tanggal Mulai:</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="end_date">Tanggal Akhir:</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-printer"></i> Cetak
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
