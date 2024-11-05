<?php

namespace App\Exports;

use App\Models\BarangKeluar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BarangKeluarExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    private $startDate;
    private $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Fetch the data
        $barangKeluar = BarangKeluar::whereBetween('tanggal', [$this->startDate, $this->endDate])->get();

        // Prepend headings to the collection
        $barangKeluar->prepend($this->headings());

        // Return the collection
        return $barangKeluar;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Kode Barang',
            'Tanggal',
            'Transaksi Keluar',
            'Saldo Akhir',
            'Satuan',
            'Unit',
            'Keterangan',
        ];
    }

    public function map($barangKeluar): array
    {
        // Check if $barangKeluar is an array (heading)
        if (is_array($barangKeluar)) {
            return $barangKeluar; // Return heading as it is
        }

        // Map the data to array
        return [
            $barangKeluar->id,
            $barangKeluar->stokOpname->nama_barang,
            $barangKeluar->stok_opname_id,
            $barangKeluar->tanggal,
            $barangKeluar->transaksi_keluar,
            $barangKeluar->saldo_akhir,
            $barangKeluar->satuan,
            $barangKeluar->unit,
            $barangKeluar->keterangan,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                // Add title at A1
                $sheet->setCellValue('A1', "Jumlah Transaksi Keluar dari tanggal {$this->startDate} hingga {$this->endDate}");
                // Merge cells for the title
                $sheet->mergeCells('A1:I1');
                // Set title font bold
                $sheet->getStyle('A1')->getFont()->setBold(true);
                // Set headings starting from A2
                $sheet->fromArray($this->headings(), null, 'A2');
            },
        ];
    }
}
