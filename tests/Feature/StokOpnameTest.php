<?php

namespace Tests\Feature;

use App\Models\BarangMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StokOpnameTest extends TestCase
{
    use RefreshDatabase;

    /**@test */
    public function test_it_displays_stok_opname_with_pagination()
    {
        // Setup: Buat pengguna dan login
        $user = User::factory()->create();
        $this->actingAs($user); // Simulasi login

        // Buat entri stok opname
        BarangMasuk::factory()->count(10)->create(); // Mengisi database dengan data

        // Act: Mengunjungi rute stok opname
        $response = $this->get('/stok_opname');

        // Assert: Memastikan status 200 OK
        $response->assertStatus(200);
    }

    // Tambahkan pengujian lain sesuai kebutuhan
}

