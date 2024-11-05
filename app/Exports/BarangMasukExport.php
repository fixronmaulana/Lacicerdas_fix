<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BarangMasukExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    private $startDate;
    private $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Fetch the data
        $barangMasuk = BarangMasuk::whereBetween('tanggal', [$this->startDate, $this->endDate])->get();

        // Prepend headings to the collection
        $barangMasuk->prepend($this->headings());

        // Return the collection
        return $barangMasuk;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Tanggal',
            'Transaksi Masuk',
            'Saldo Akhir',
            'Keterangan',
        ];
    }

    public function map($barangMasuk): array
    {
        // Check if $barangMasuk is an array (heading)
        if (is_array($barangMasuk)) {
            return $barangMasuk; // Return heading as it is
        }

        // Map the data to array
        return [
            $barangMasuk->id,
            $barangMasuk->nama_barang,
            $barangMasuk->tanggal,
            $barangMasuk->transaksi_masuk,
            $barangMasuk->saldo_akhir,
            $barangMasuk->keterangan,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                // Add title at A1
                $sheet->setCellValue('A1', "Jumlah Transaksi Masuk dari tanggal {$this->startDate} hingga {$this->endDate}");
                // Merge cells for the title
                $sheet->mergeCells('A1:G1');
                // Set title font bold
                $sheet->getStyle('A1')->getFont()->setBold(true);
                // Set headings starting from A2
                $sheet->fromArray($this->headings(), null, 'A2');
            },
        ];
    }
}
