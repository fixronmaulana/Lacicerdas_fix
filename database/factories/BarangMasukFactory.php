<?php

namespace Database\Factories;

use App\Models\BarangMasuk;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangMasukFactory extends Factory
{
    protected $model = BarangMasuk::class;

    public function definition()
    {
        return [
            'nama_barang' => $this->faker->word(),
            'satuan' => $this->faker->randomElement(['pcs', 'kg', 'liter']), // Misalnya, beberapa satuan
            'tanggal' => $this->faker->date(),
            'transaksi_masuk' => $this->faker->numberBetween(1, 100), // Nilai transaksi masuk antara 1-100
            'saldo_akhir' => $this->faker->numberBetween(0, 1000), // Nilai saldo akhir antara 0-1000
            'keterangan' => $this->faker->sentence(), // Kalimat acak untuk keterangan
        ];
    }
}
