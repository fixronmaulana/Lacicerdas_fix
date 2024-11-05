@extends('layouts.base')

@section('content')

<div class="container">
    <h1 class="m-0 font-weight-bold text-dark">History Barang</h1>

    <div class="card-header py-3">
        <h3 class="m-0 font-weight-bold text-dark">Nama Barang: {{ $stokOpname->nama_barang }}</h3>
        <h3 class="m-0 font-weight-bold text-dark">Satuan: {{ $stokOpname->satuan }}</h3>
        <h3 class="m-0 font-weight-bold text-dark">Kode Barang: {{ $stokOpname->kode_barang }}</h3>
    </div>



    <!-- Tabel Barang Masuk dan Keluar -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>No Dokumen</th>
                <th colspan="2">Masuk</th>
                <th colspan="2">Keluar</th>
                <th colspan="2">Saldo</th>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Unit</th>
                    <th>Jumlah</th>
                    <th>Unit</th>
                    <th>Jumlah</th>
                    <th>Unit</th>
                    <th>Jumlah</th>
                </tr>

            </tr>
        </thead>
        <tbody>
            <!-- Loop Data Barang Masuk -->
            @forelse($stokOpname->barangMasuk as $masuk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $masuk->tanggal }}</td>
                <td>{{ $masuk->keterangan }}</td>
                <td>-</td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>-</td> <!-- Kosongkan jika unit tidak ada di barang masuk -->
                <td>{{ $masuk->transaksi_masuk}}</td>
                <td></td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>-</td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>-</td><!--Keluar-->
                <td>{{ $masuk->saldo_akhir }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data barang masuk</td>
            </tr>
            @endforelse

            <!-- Loop Data Barang Keluar -->
            @forelse($stokOpname->barangKeluar as $keluar)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $keluar->tanggal }}</td>
                <td>{{ $keluar->keterangan }}</td>
                <td>-</td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>-</td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>-</td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>{{ $keluar->unit}}</td>
                <td>{{ $keluar->transaksi_keluar }}</td>
                <td>-</td> <!-- Kosongkan jika tidak ada barang masuk -->
                <td>{{ $keluar->saldo_akhir }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data barang keluar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
