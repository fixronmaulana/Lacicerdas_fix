@extends('layouts.base')

@section('content')
    <div class="container-fluid">

        <h3><b>Kode Barang: {{ $item->kode_barang }}</b></h3>
        <h3><b>Nama Barang: {{ $item->nama_barang }}</b></h3>
        <h3><strong>Satuan: {{ $item->satuan }}</strong></h3>

        <br>
        <h3>Rincian History Barang</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Tanggal</th>
                    <th rowspan="2">Keterangan</th>
                    <th colspan="2" class="text-center">Masuk</th>
                    <th colspan="2" class="text-center">Keluar</th>
                    <th rowspan="2" class="text-center">Saldo Persediaan</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-center">Jumlah</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Unit</th>
                </tr>
            </thead>
            <tbody>
                @php $saldo = 0; @endphp
                @foreach ($allTransactionsPaginate as $index => $trans)
                    @php
                        $saldo += $trans['masuk'] - $trans['keluar']; // Update saldo berdasarkan transaksi
                    @endphp
                    <tr>
                        <td>{{ ($allTransactionsPaginate->currentPage() - 1) * $allTransactionsPaginate->perPage() + $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($trans['tanggal'])->format('d-m-Y') }}</td>
                        <td>{{ $trans['keterangan'] }}</td>
                        <td colspan="2" class="text-center">{{ $trans['masuk'] ?: '-' }}</td>
                        <td class="text-center">{{ $trans['keluar'] ?: '-' }}</td>
                        <td class="text-center">{{ $trans['unit'] }}</td>
                        <td class="text-center">{{ $saldo }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-center">Jumlah</th>
                    <th class="text-center">{{ $allTransactionsPaginate->sum('masuk') }}</th>
                    <th class="text-center">{{ $allTransactionsPaginate->sum('keluar') }}</th>
                    <th>-</th>
                    <th class="text-center">{{ $saldo }}</th>
                </tr>
            </tfoot>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $allTransactionsPaginate->links() }}
        </div>

        <a href="{{ route('history.barang.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection
