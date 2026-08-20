<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::get('/',          [PublicController::class, 'index'])->name('home');
Route::get('/lomba/{gender}', [PublicController::class, 'kategori'])
     ->where('gender', 'putra|putri')
     ->name('lomba.kategori');
Route::get('/lomba/{gender}/merchandise', [PublicController::class, 'merchandise'])
     ->where('gender', 'putra|putri')
     ->name('merchandise.index');
Route::get('/daftar',    [PublicController::class, 'showForm'])->name('daftar.form');
Route::post('/daftar',   [PublicController::class, 'store'])->name('daftar.store');
Route::get('/daftar/sukses', [PublicController::class, 'sukses'])->name('daftar.sukses');
Route::get('/api/lomba', [PublicController::class, 'getLomba'])->name('api.lomba');

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')
     ->middleware('throttle:5,1'); // SECURITY: max 5 attempts per minute per IP
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.admin', 'gender.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pendaftar',                         [PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/pendaftar/export',                  [PendaftarController::class, 'export'])->name('pendaftar.export');
    Route::get('/pendaftar/{pendaftar}',             [PendaftarController::class, 'show'])->name('pendaftar.show');
    Route::patch('/pendaftar/{pendaftar}/verifikasi',[PendaftarController::class, 'verifikasi'])->name('pendaftar.verifikasi');
    Route::delete('/pendaftar/{pendaftar}',          [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
    Route::get('/uploads/{folder}/{filename}',       [PendaftarController::class, 'serveFile'])->name('uploads.serve')
         ->where('folder','kartu_siswa|bukti_pembayaran');

    Route::get('/lomba',               [LombaController::class, 'index'])->name('lomba.index');
    Route::get('/lomba/create',        [LombaController::class, 'create'])->name('lomba.create');
    Route::post('/lomba',              [LombaController::class, 'store'])->name('lomba.store');
    Route::get('/lomba/{lomba}/edit',  [LombaController::class, 'edit'])->name('lomba.edit');
    Route::put('/lomba/{lomba}',       [LombaController::class, 'update'])->name('lomba.update');
    Route::patch('/lomba/{lomba}/toggle',[LombaController::class,'toggle'])->name('lomba.toggle');
    Route::delete('/lomba/{lomba}',    [LombaController::class, 'destroy'])->name('lomba.destroy');

    // Delete khusus file (gambar & guidebook) pada lomba
    Route::delete('/lomba/{lomba}/gambar',    [LombaController::class, 'deleteGambar'])->name('lomba.delete.gambar');
    Route::delete('/lomba/{lomba}/guidebook', [LombaController::class, 'deleteGuidebook'])->name('lomba.delete.guidebook');

    Route::get('/settings',  [SettingController::class, 'index'])->name('settings');

    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/users',             [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',      [UserController::class, 'create'])->name('users.create');
    Route::post('/users',            [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',      [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',   [UserController::class, 'destroy'])->name('users.destroy');

    // Sponsor (superadmin, admin_putra & admin_putri; pengecekan akses ada di SponsorController)
    Route::get('/sponsor',                 [\App\Http\Controllers\SponsorController::class, 'index'])->name('sponsor.index');
    Route::get('/sponsor/create',        [\App\Http\Controllers\SponsorController::class, 'create'])->name('sponsor.create');
    Route::post('/sponsor',              [\App\Http\Controllers\SponsorController::class, 'store'])->name('sponsor.store');
    Route::get('/sponsor/{sponsor}/edit',[\App\Http\Controllers\SponsorController::class, 'edit'])->name('sponsor.edit');
    Route::put('/sponsor/{sponsor}',     [\App\Http\Controllers\SponsorController::class, 'update'])->name('sponsor.update');
    Route::delete('/sponsor/{sponsor}',  [\App\Http\Controllers\SponsorController::class, 'destroy'])->name('sponsor.destroy');

    // Kelola Pembayaran (rekening bank publik; ter-scope gender via gender.access middleware)
    Route::get('/pembayaran',                  [\App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create',            [\App\Http\Controllers\PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran',                  [\App\Http\Controllers\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{rekening}/edit',   [\App\Http\Controllers\PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/{rekening}',        [\App\Http\Controllers\PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('/pembayaran/{rekening}',     [\App\Http\Controllers\PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    // Kelola Merchandise (ter-scope gender via gender.access middleware, sama pola Pembayaran)
    Route::get('/merchandise',                    [\App\Http\Controllers\MerchandiseController::class, 'index'])->name('merchandise.index');
    Route::get('/merchandise/create',             [\App\Http\Controllers\MerchandiseController::class, 'create'])->name('merchandise.create');
    Route::post('/merchandise',                   [\App\Http\Controllers\MerchandiseController::class, 'store'])->name('merchandise.store');
    Route::get('/merchandise/{merchandise}/edit', [\App\Http\Controllers\MerchandiseController::class, 'edit'])->name('merchandise.edit');
    Route::put('/merchandise/{merchandise}',      [\App\Http\Controllers\MerchandiseController::class, 'update'])->name('merchandise.update');
    Route::delete('/merchandise/{merchandise}',   [\App\Http\Controllers\MerchandiseController::class, 'destroy'])->name('merchandise.destroy');
});

