<h2 class="h3 mb-3 text-gray-800 text-center">Laporan Barang Keluar</h2>

{{-- Menampilkan rentang tanggal --}}
<p class="text-center">Periode: {{ $startDate }} - {{ $endDate }}</p>
 <!-- Styling khusus untuk tampilan PDF -->
 <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }
    .container-fluid {
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }
    th {
        background-color: #87ceeb;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .text-center {
        text-align: center;
    }
    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Tanggal</th>
            <th>Transaksi Keluar</th>
            <th>Satuan</th>
            <th>Unit</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barangKeluar as $index => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->stokOpname->nama_barang }}</td>
                <td>{{ $item->stok_opname_id }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->transaksi_keluar }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
