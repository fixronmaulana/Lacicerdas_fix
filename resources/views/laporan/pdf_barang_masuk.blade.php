<h2 class="h3 mb-3 text-gray-800 text-center">Laporan Barang Masuk</h2>

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
            <th>Tanggal</th>
            <th>Transaksi Masuk</th>
            <th>Saldo Akhir</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barangMasuk as $index => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->transaksi_masuk }}</td>
                <td>{{ $item->saldo_akhir }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
