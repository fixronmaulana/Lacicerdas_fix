@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Beranda</h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Barang Masuk</h6>
                </div>
                <div class="card-body">
                    @if ($barangMasukToday->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableBarangMasuk" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Tanggal</th>
                                        <th>Transaksi Masuk</th>
                                        <th>Saldo Akhir</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangMasukToday as $index => $barangMasuk)
                                        <tr>
                                            <td>{{ $barangMasukToday->firstItem() + $index }}</td>
                                            <td>{{ $barangMasuk->nama_barang }}</td>
                                            <td>{{ $barangMasuk->satuan }}</td>
                                            <td>{{ $barangMasuk->tanggal }}</td>
                                            <td>{{ $barangMasuk->transaksi_masuk }}</td>
                                            <td>{{ $barangMasuk->saldo_akhir }}</td>
                                            <td>{{ $barangMasuk->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex">
                            {{ $barangMasukToday->appends(['barangKeluarPage' => $barangKeluarToday->currentPage()])->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <p class="text-danger">Tidak ada transaksi barang masuk hari ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Barang Keluar</h6>
                </div>
                <div class="card-body">
                    @if ($barangKeluarToday->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableBarangKeluar" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Tanggal</th>
                                        <th>Transaksi Keluar</th>
                                        <th>Saldo Akhir</th>
                                        <th>Satuan</th>
                                        <th>Unit</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangKeluarToday as $index => $barangKeluar)
                                        <tr>
                                            <td>{{ $barangKeluarToday->firstItem() + $index }}</td>
                                            <td>{{ $barangKeluar->stokOpname->nama_barang }}</td>
                                            <td>{{ $barangKeluar->stok_opname_id }}</td>
                                            <td>{{ $barangKeluar->tanggal }}</td>
                                            <td>{{ $barangKeluar->transaksi_keluar }}</td>
                                            <td>{{ $barangKeluar->saldo_akhir }}</td>
                                            <td>{{ $barangKeluar->satuan }}</td>
                                            <td>{{ $barangKeluar->unit }}</td>
                                            <td>{{ $barangKeluar->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex">
                            {{ $barangKeluarToday->appends(['barangMasukPage' => $barangMasukToday->currentPage()])->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <p class="text-danger">Tidak ada transaksi barang keluar hari ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTableBarangMasuk').DataTable();
        $('#dataTableBarangKeluar').DataTable();
    });

    // Refresh halaman setiap hari pada jam 00:00
    setInterval(function() {
        var now = new Date();
        if (now.getHours() === 0 && now.getMinutes() === 0 && now.getSeconds() === 0) {
            location.reload();
        }
    }, 1000); // Cek setiap detik
</script>
@endsection
