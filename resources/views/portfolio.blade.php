<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nanda · DevJourney | Portofolio Interaktif</title>
    <meta name="description" content="Portofolio perjalanan belajar Fullstack Web Development selama 12 bulan.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portfolio-interactive.css') }}?v=1">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="portfolio-page" id="top">
    <a href="#timeline" class="skip-link">Lewati ke proyek</a>
    <div class="reading-progress" aria-hidden="true"><span id="reading-progress"></span></div>
    <!-- Background Elements -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <header class="hero">
        <nav aria-label="Navigasi utama">
            <a href="#top" class="logo">DevJourney<span>.</span></a>
            <div class="nav-links">
                <a href="/request-website" class="nav-btn">Buat Website Custom</a>
                <a href="#timeline" class="nav-btn">Lihat Roadmap</a>
                <button id="theme-toggle" class="nav-btn" type="button" hidden aria-label="Aktifkan tema terang">☀</button>
            </div>
        </nav>
        <div class="hero-content">
            <div class="hero-label"><span></span> NANDA / DEVELOPER PORTFOLIO</div>
            <h1 class="reveal-text">12 Bulan Menuju <br><span class="gradient-text">Fullstack Developer</span></h1>
            <p class="fade-in-up">Jejak langkah, proyek, dan pencapaian selama satu tahun penuh dedikasi dalam dunia Web Development.</p>
            <a href="#timeline" class="cta-button fade-in-up delay-1">Mulai Eksplorasi <i class="fas fa-arrow-down"></i></a>
            <a href="/pkl" class="hero-secondary">Coba proyek akhir ↗</a>
            <div class="journey-stats"><div><strong>12</strong><span>Bulan perjalanan</span></div><div><strong>11</strong><span>Tahap proyek</span></div><div><strong>01</strong><span>Proyek akhir</span></div></div>
        </div>
        <a class="scroll-cue" href="#timeline">SCROLL UNTUK MENJELAJAHI <span>↓</span></a>
    </header>

    <main>
        <section id="timeline" class="timeline-section">
            <div class="container">
                <h2 class="section-title">Roadmap Pembelajaran</h2>
                <p class="section-intro">Dari baris HTML pertama hingga aplikasi fullstack. Pilih bidang, temukan proyek, dan jelajahi prosesnya.</p>
                <div id="explorer" class="explorer" hidden>
                    <div class="explorer-top"><div class="project-search"><label for="project-search">Cari proyek atau teknologi</label><input id="project-search" type="search" placeholder="Coba Laravel, kasir, atau bulan 3…" autocomplete="off"></div><div class="view-switch" role="group" aria-label="Tampilan proyek"><button type="button" data-view="timeline" aria-pressed="true">↕ Timeline</button><button type="button" data-view="grid" aria-pressed="false">▦ Grid</button></div></div>
                    <div class="filter-row" role="group" aria-label="Filter bidang proyek"><button data-filter="all" aria-pressed="true">Semua proyek</button><button data-filter="frontend" aria-pressed="false">Frontend</button><button data-filter="php" aria-pressed="false">PHP & Database</button><button data-filter="laravel" aria-pressed="false">Laravel</button><button data-filter="saved" aria-pressed="false">♡ Tersimpan</button></div>
                    <div class="explorer-bottom"><p id="result-count" role="status" aria-live="polite"></p><div class="exploration"><span id="explored-label">0 / 11 proyek dibuka</span><progress id="explored-progress" max="11" value="0" aria-label="Proyek yang sudah dibuka"></progress></div></div>
                </div>
                <div id="no-projects" class="no-projects" hidden><h3>Belum ada proyek yang cocok.</h3><p>Coba kata kunci lain atau simpan proyek dengan tombol hati.</p><button type="button" id="reset-filters" class="project-btn">Tampilkan semua proyek</button></div>
                
                <div class="timeline">
                    <!-- Bulan 1 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 1 &mdash; HTML</div>
                            <h3>Portofolio Pribadi</h3>
                            <ul class="skills">
                                <li>HTML dasar</li>
                                <li>Semantic HTML</li>
                                <li>Form & Table</li>
                                <li>Struktur website</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Website portofolio sederhana</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-1/index.html" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 2 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 2 &mdash; CSS</div>
                            <h3>Landing Page Sekolah</h3>
                            <ul class="skills">
                                <li>CSS dasar</li>
                                <li>Flexbox</li>
                                <li>Grid</li>
                                <li>Responsive Design</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Landing page responsive (HP & Laptop)</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-2/index.html" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 3 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 3 &mdash; JavaScript</div>
                            <h3>Aplikasi To-Do List</h3>
                            <ul class="skills">
                                <li>DOM Manipulation</li>
                                <li>Event Listener</li>
                                <li>Local Storage</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Aplikasi To-Do List fungsional dan lengkap</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-3/index.html" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 4 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 4 &mdash; PHP Dasar</div>
                            <h3>Buku Tamu Digital</h3>
                            <ul class="skills">
                                <li>GET & POST</li>
                                <li>Session</li>
                                <li>Include</li>
                                <li>Validasi Form</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Data tersimpan dan ditampilkan dengan baik</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-4/index.php" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 5 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 5 &mdash; MySQL</div>
                            <h3>Sistem Perpustakaan</h3>
                            <ul class="skills">
                                <li>Database Design</li>
                                <li>CRUD Operations</li>
                                <li>Relasi Dasar</li>
                                <li>Sistem Login</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Sistem perpustakaan sederhana beroperasi</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-5/index.php" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 6 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 6 &mdash; Bootstrap</div>
                            <h3>Upgrade Perpustakaan</h3>
                            <ul class="skills">
                                <li>Bootstrap Framework</li>
                                <li>Navbar & Card</li>
                                <li>Modal Components</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: UI lebih profesional, cepat, dan responsif</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-6/index.php" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 7 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 7 &mdash; PHP Native Menengah</div>
                            <h3>Sistem Kasir (POS)</h3>
                            <ul class="skills">
                                <li>Relasi Database Lanjutan</li>
                                <li>Transaksi Logic</li>
                                <li>Pembuatan Laporan</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Kasir lengkap beserta fitur cetak struk</span>
                            </div>
                            <div class="project-link-container">
                                <a href="/bulan-7/index.php" target="_blank" class="project-btn">Lihat Proyek <i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 8 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 8 &mdash; Laravel Dasar</div>
                            <h3>Inventaris Sekolah</h3>
                            <a href="/bulan-8" class="project-btn">Buka Inventaris ↗</a>
                            <ul class="skills">
                                <li>Routing & MVC</li>
                                <li>Migration & Seeder</li>
                                <li>Eloquent ORM</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: CRUD Inventaris berjalan di Laravel</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 9 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 9 &mdash; Tailwind CSS</div>
                            <h3>Upgrade Inventaris</h3>
                            <a href="/bulan-9" class="project-btn">Buka Dashboard Tailwind ↗</a>
                            <ul class="skills">
                                <li>Tailwind Utility-first</li>
                                <li>Dashboard Modern</li>
                                <li>Responsive Layout</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Tampilan sekelas aplikasi modern profesional</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 10 -->
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content glass-card">
                            <div class="month-badge">Bulan 10 &mdash; Laravel Menengah</div>
                            <h3>Sistem Booking Lapangan</h3>
                            <a href="/bulan-10" class="project-btn">Booking Lapangan ↗</a>
                            <ul class="skills">
                                <li>Authentication Lanjut</li>
                                <li>Middleware</li>
                                <li>Relasi Database Kompleks</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-bullseye"></i> <span>Target: Sistem booking jadwal yang aman dan lengkap</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bulan 11 & 12 -->
                    <div class="timeline-item highlight-item">
                        <div class="timeline-dot pulse-dot"></div>
                        <div class="timeline-content glass-card premium-card">
                            <div class="month-badge glow-badge">Bulan 11 & 12 &mdash; Laravel Lanjutan</div>
                            <h3>PKLFinder <span class="tag">(Proyek Utama)</span></h3>
                            <a href="/bulan-11" class="project-btn">Jelajahi PKLFinder ↗</a>
                            <a href="/bulan-12" class="project-btn">Dashboard & Laporan ↗</a>
                            <ul class="skills grid-skills">
                                <li>Multi Role System</li>
                                <li>Upload File Manajemen</li>
                                <li>Search & Filter</li>
                                <li>Pagination Data</li>
                                <li>Dashboard Statistik</li>
                                <li>Export PDF & Excel</li>
                            </ul>
                            <div class="target">
                                <i class="fas fa-trophy"></i> <span>Proyek akhir: lowongan, lamaran, multi-role, dan laporan</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
    <dialog id="project-dialog" aria-labelledby="dialog-title"><div class="dialog-header"><span id="dialog-month" class="month-badge"></span><button id="close-dialog" type="button" aria-label="Tutup detail proyek">×</button></div><h2 id="dialog-title"></h2><p id="dialog-description"></p><h3>Yang dipelajari</h3><ul id="dialog-skills" class="skills"></ul><div id="dialog-links" class="dialog-links"></div><div class="dialog-navigation"><button id="previous-project" type="button">← Sebelumnya</button><span id="dialog-position"></span><button id="next-project" type="button">Berikutnya →</button></div></dialog>
    <a href="#top" id="back-to-top" class="back-to-top" aria-label="Kembali ke atas" hidden>↑</a>

    <footer>
        <div class="container">
            <p>&copy; 2026 DevJourney. Dibuat dengan dedikasi dan kode.</p>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a class="project-btn" href="/laporan-website" style="margin-top:15px">Laporan permintaan website ↗</a>
            @endif
        </div>
    </footer>

    <script src="{{ asset('js/portfolio-interactive.js') }}?v=1" defer></script>
</body>
</html>
