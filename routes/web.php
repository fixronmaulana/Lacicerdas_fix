<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Imports\BarangMasukImport;
use App\Http\Controllers\HistoryBarangController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// // Welcome Route (Optional)
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Dashboard route
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->middleware(['auth', 'role:admin'])->name('admin.dashboard');

// Transaksi routes
Route::resource('stok_opname', StokOpnameController::class);

Route::get('/barang_masuk', [BarangMasukController::class, 'index'])->name('barang_masuk.index');
Route::get('/barang_masuk/create', [BarangMasukController::class, 'create'])->name('barang_masuk.create');
Route::post('/barang_masuk', [BarangMasukController::class, 'store'])->name('barang_masuk.store');
Route::get('/barang_masuk/{barang_masuk}/edit', [BarangMasukController::class, 'edit'])->name('barang_masuk.edit');
Route::put('/barang_masuk/{barang_masuk}', [BarangMasukController::class, 'update'])->name('barang_masuk.update');
Route::delete('/barang_masuk/{barang_masuk}', [BarangMasukController::class, 'destroy'])->name('barang_masuk.destroy');

Route::resource('barang_keluar', BarangKeluarController::class);

// History route
Route::get('/history-barang', [HistoryBarangController::class, 'index'])->name('history.barang.index');


// Laporan routes
Route::get('laporan/barang_masuk', [LaporanController::class, 'viewBarangMasuk'])->name('laporan.barang_masuk');
Route::get('laporan/barang_keluar', [LaporanController::class, 'viewBarangKeluar'])->name('laporan.barang_keluar');
Route::get('laporan/export-barang-masuk', [LaporanController::class, 'exportBarangMasuk'])->name('laporan.exportBarangMasuk');
Route::get('laporan/export-barang-keluar', [LaporanController::class, 'exportBarangKeluar'])->name('laporan.exportBarangKeluar');

//Import
Route::post('/barang-masuk/import', [BarangMasukController::class, 'import'])->name('barang_masuk.import');

//Histori Barang Route
Route::get('/admin/historibarang', [HistoryBarangController::class, 'index'])->name('admin.histori_barang.index');
// In routes/web.php
Route::get('histori_barang/{id}', [HistoryBarangController::class, 'show'])->name('admin.histori_barang.show');


// User dashboard
Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth', 'role:user'])->name('user.dashboard');

// Default dashboard for authenticated users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard');
});
