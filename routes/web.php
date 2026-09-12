<?php

use App\Http\Controllers\Inventaris\AuthController as InventarisAuthController;
use App\Http\Controllers\Inventaris\BarangController;
use App\Http\Controllers\Inventaris\DashboardController;
use App\Http\Controllers\Inventaris\KategoriController;
use App\Http\Controllers\Inventaris\LaporanController;
use App\Http\Controllers\WhatsAppController;
use App\Mail\WebsiteRequestMail;
use App\Models\WebsiteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

require __DIR__.'/journey.php';

Route::get('/', function () {
    return view('portfolio');
});

Route::get('/request-website', function () {
    return view('request-website');
})->name('request-website');

Route::post('/request-website', function (Request $request) {
    $request->validate([
        'nama' => 'required|string|max:255',
        'wa' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\-\s]+$/'],
        'email' => 'nullable|email|max:255',
        'nama_website' => 'nullable|string|max:255',
        'tujuan_website' => 'nullable|string|max:3000',
        'deskripsi_usaha' => 'nullable|string|max:5000',
        'catatan' => 'nullable|string|max:5000',
        'target_tanggal' => 'nullable|date|after_or_equal:today',
    ]);

    $websiteRequest = WebsiteRequest::create([
        'nama' => $request->input('nama'),
        'perusahaan' => $request->input('perusahaan'),
        'wa' => $request->input('wa'),
        'email' => $request->input('email'),
        'alamat' => $request->input('alamat'),
        'nama_website' => $request->input('nama_website'),
        'tujuan_website' => $request->input('tujuan_website'),
        'deskripsi_usaha' => $request->input('deskripsi_usaha'),
        'target_pengguna' => $request->input('target_pengguna'),
        'umur_target' => $request->input('umur_target'),
        'wilayah_target' => $request->input('wilayah_target'),
        'jenis_website' => $request->input('jenis_website') ? json_encode($request->input('jenis_website')) : null,
        'fitur' => $request->input('fitur') ? json_encode($request->input('fitur')) : null,
        'halaman' => $request->input('halaman') ? json_encode($request->input('halaman')) : null,
        'warna_utama' => $request->input('warna_utama'),
        'warna_kedua' => $request->input('warna_kedua'),
        'font' => $request->input('font'),
        'referensi' => $request->input('referensi'),
        'logo_tersedia' => $request->input('logo_tersedia'),
        'teks_tersedia' => $request->input('teks_tersedia'),
        'foto_tersedia' => $request->input('foto_tersedia'),
        'domain_tersedia' => $request->input('domain_tersedia'),
        'hosting_tersedia' => $request->input('hosting_tersedia'),
        'budget' => $request->input('budget'),
        'target_tanggal' => $request->input('target_tanggal'),
        'estimasi_harga' => $request->input('estimasi_harga'),
        'catatan' => $request->input('catatan'),
        'persetujuan_nama' => $request->input('persetujuan_nama'),
        'persetujuan_tanggal' => $request->input('persetujuan_tanggal'),
        'tanda_tangan' => $request->input('tanda_tangan'),
    ]);

    $recipient = env('WEBSITE_REQUEST_EMAIL', 'your-email@example.com');
    Mail::to($recipient)->send(new WebsiteRequestMail($websiteRequest));

    $whatsapp = app(WhatsAppController::class);
    $whatsapp->sendRequestNotification($websiteRequest);
    if ($websiteRequest->wa) {
        $whatsapp->sendAutoReply($websiteRequest->wa);
    }

    return redirect()->route('request-website')->with('success', 'Permintaan website Anda berhasil diterima. Tim kami akan segera menghubungi Anda.');
})->middleware('throttle:5,1')->name('request-website.store');

Route::post('/whatsapp/webhook', [WhatsAppController::class, 'handleIncomingMessage']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/website-requests', function () {
        $requests = WebsiteRequest::latest()->get();

        return view('admin.website-requests', compact('requests'));
    });

    Route::get('/laporan-website', function () {
        $requests = WebsiteRequest::latest()->get();

        return view('reports.website-requests', compact('requests'));
    });
});

// ── Bulan 8: Inventaris Sekolah ──────────────────────────
Route::prefix('inventaris')->group(function () {
    // Auth
    Route::get('login', [InventarisAuthController::class, 'showLogin'])->name('inventaris.login');
    Route::post('login', [InventarisAuthController::class, 'login']);
    Route::get('register', [InventarisAuthController::class, 'showRegister'])->name('inventaris.register');
    Route::post('register', [InventarisAuthController::class, 'register']);
    Route::post('logout', [InventarisAuthController::class, 'logout'])->name('inventaris.logout');

    // Protected
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('inventaris.dashboard');
        Route::resource('kategori', KategoriController::class)->except('show')->names('inventaris.kategori');
        Route::resource('barang', BarangController::class)->names('inventaris.barang');
        Route::get('laporan', [LaporanController::class, 'index'])->name('inventaris.laporan');
    });
});
