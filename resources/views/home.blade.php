<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rancang Bangun Website Arsip Aris di RRI Batam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        aris: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-white text-slate-900 min-h-screen flex flex-col">

    {{-- ================= NAVBAR ================= --}}
    <nav class="w-full border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white font-black text-sm">
                        RRI
                    </div>
                    <span class="font-extrabold text-xl tracking-tight">
                        ARSIP<span class="text-aris-600">ARIS</span>
                    </span>
                </div>

                <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-500">
                    <a href="#tentang" class="hover:text-slate-900 transition-colors">Tentang</a>
                    <a href="#fitur" class="hover:text-slate-900 transition-colors">Fitur</a>
                    <a href="#alur" class="hover:text-slate-900 transition-colors">Alur Kerja</a>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard.index') }}" class="font-bold text-sm text-white bg-slate-900 px-5 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="font-bold text-sm text-white bg-slate-900 px-5 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">
                                Masuk Sistem
                            </a>
                        @endauth
                    @endif
                </div>

            </div>
        </div>
    </nav>

    {{-- ================= HERO ================= --}}
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
            <div class="flex flex-col lg:flex-row items-center gap-14">

                {{-- Text kiri --}}
                <div class="w-full lg:w-1/2 space-y-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-aris-50 border border-aris-100 text-aris-600 text-xs font-bold uppercase tracking-wide">
                        <i class="fas fa-broadcast-tower"></i> RRI Stasiun Batam
                    </div>

                    <h1 class="text-4xl sm:text-5xl font-extrabold leading-[1.12] tracking-tight text-slate-900">
                        Rancang Bangun<br>
                        Website Arsip Aris<br>
                        di RRI Batam
                    </h1>

                    <p class="text-lg text-slate-500 leading-relaxed max-w-md">
                        ARIS (Arsip Digital) adalah sistem pengelolaan arsip berbasis website yang dirancang untuk membantu RRI Stasiun Batam dalam menyimpan, mengklasifikasi, dan menelusuri dokumen arsip secara cepat, aman, dan terstruktur — menggantikan pencatatan manual yang selama ini digunakan.
                    </p>

                    <div class="flex flex-col sm:flex-row items-start gap-4 pt-2">
                        @auth
                            <a href="{{ route('dashboard.index') }}" class="font-bold text-white bg-slate-900 px-7 py-3.5 rounded-lg hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-box-archive"></i> Buka Sistem Arsip
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="font-bold text-white bg-slate-900 px-7 py-3.5 rounded-lg hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-box-archive"></i> Masuk ke Sistem
                            </a>
                        @endauth
                        <a href="#tentang" class="font-bold text-slate-900 bg-white border-2 border-slate-900 px-7 py-3 rounded-lg hover:bg-slate-900 hover:text-white transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-circle-info"></i> Pelajari Sistem
                        </a>
                    </div>
                </div>

                {{-- Ilustrasi kanan --}}
                <div class="w-full lg:w-1/2 flex justify-center">
                    <div class="relative w-full max-w-md flex items-center justify-center">
                        <div class="absolute w-[420px] h-[420px] rounded-full border-2 border-aris-100"></div>
                        <div class="absolute w-[320px] h-[320px] rounded-full border-2 border-aris-100"></div>

                        <div class="relative w-64 sm:w-72 h-64 sm:h-72 bg-slate-900 rounded-3xl shadow-xl flex flex-col items-center justify-center gap-4 text-white">
                            <i class="fas fa-folder-open text-6xl text-aris-500"></i>
                            <span class="font-bold text-sm tracking-widest uppercase">Arsip Digital</span>
                            <span class="text-xs text-slate-400">RRI Batam</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ================= TENTANG ================= --}}
    <section id="tentang" class="bg-slate-50 py-20 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-aris-50 border border-aris-100 text-aris-600 text-xs font-bold uppercase tracking-wide mb-4">
                        <i class="fas fa-lightbulb"></i> Latar Belakang
                    </div>
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-4">Mengapa Arsip Digital Dibutuhkan?</h2>
                    <p class="text-slate-500 leading-relaxed mb-4">
                        Pengelolaan arsip di RRI Stasiun Batam selama ini masih banyak dilakukan secara manual, mulai dari pencatatan buku agenda hingga penyimpanan fisik dokumen. Cara ini rentan terhadap kehilangan data, kesulitan pencarian, dan keterbatasan ruang penyimpanan.
                    </p>
                    <p class="text-slate-500 leading-relaxed">
                        Melalui website ARIS, proses pengarsipan surat, dokumen, dan berkas kedinasan dapat dilakukan secara digital — mempermudah pencarian, meningkatkan keamanan data, serta mendukung efisiensi kerja pegawai RRI Batam.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center">
                        <i class="fas fa-file-shield text-3xl text-aris-600 mb-3"></i>
                        <div class="font-bold text-slate-900 text-sm">Keamanan Data</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center">
                        <i class="fas fa-magnifying-glass text-3xl text-aris-600 mb-3"></i>
                        <div class="font-bold text-slate-900 text-sm">Pencarian Cepat</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center">
                        <i class="fas fa-cloud text-3xl text-aris-600 mb-3"></i>
                        <div class="font-bold text-slate-900 text-sm">Akses Digital</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 text-center">
                        <i class="fas fa-sitemap text-3xl text-aris-600 mb-3"></i>
                        <div class="font-bold text-slate-900 text-sm">Klasifikasi Terstruktur</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FITUR ================= --}}
    <section id="fitur" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-aris-50 border border-aris-100 text-aris-600 text-xs font-bold uppercase tracking-wide mb-4">
                    <i class="fas fa-layer-group"></i> Fitur Utama Sistem
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4">Kemampuan Website ARIS</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Dirancang khusus untuk mendukung alur kerja kearsipan di lingkungan RRI Stasiun Batam, dari input dokumen hingga pelaporan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="bg-white rounded-2xl p-8 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border border-slate-100">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-aris-600 mb-6">
                        <i class="fas fa-upload text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Input & Unggah Arsip</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Pegawai dapat mengunggah dokumen arsip beserta metadata seperti nomor surat, tanggal, kategori, dan unit kerja terkait secara langsung melalui website.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border border-slate-100">
                    <div class="w-14 h-14 bg-slate-200 rounded-xl flex items-center justify-center text-slate-700 mb-6">
                        <i class="fas fa-tags text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Klasifikasi & Kategori</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Setiap arsip dikelompokkan berdasarkan jenis dokumen, unit kerja, dan tahun, sehingga penelusuran data menjadi lebih rapi dan sistematis.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border border-slate-100">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mb-6">
                        <i class="fas fa-magnifying-glass text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pencarian Arsip</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Fitur pencarian memungkinkan pegawai menemukan dokumen berdasarkan kata kunci, nomor surat, atau rentang tanggal hanya dalam hitungan detik.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border border-slate-100">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 mb-6">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Hak Akses Bertingkat</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Setiap pengguna memiliki hak akses berbeda sesuai peran — admin, kepala unit, atau staf — untuk menjaga keamanan dan validitas data arsip.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border border-slate-100">
                    <div class="w-14 h-14 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600 mb-6">
                        <i class="fas fa-clock-rotate-left text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Riwayat & Log Aktivitas</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Setiap perubahan pada data arsip tercatat otomatis, memudahkan audit dan pelacakan siapa yang mengubah atau menambahkan dokumen.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 border border-slate-100">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-6">
                        <i class="fas fa-file-export text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Cetak & Ekspor Laporan</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Data arsip dapat diekspor atau dicetak dalam bentuk laporan rekapitulasi untuk kebutuhan administrasi dan pelaporan kedinasan.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= ALUR KERJA ================= --}}
    <section id="alur" class="bg-slate-900 py-20 text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-aris-500 text-xs font-bold uppercase tracking-wide mb-4">
                    <i class="fas fa-diagram-project"></i> Alur Kerja Sistem
                </div>
                <h2 class="text-3xl font-extrabold mb-4">Dari Dokumen Fisik ke Arsip Digital</h2>
                <p class="text-slate-400 max-w-2xl mx-auto">Empat tahap sederhana yang dilalui setiap dokumen sebelum menjadi arsip digital yang tertata di sistem ARIS.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                    <div class="text-aris-500 font-black text-3xl mb-3">01</div>
                    <h3 class="font-bold mb-2">Penerimaan Dokumen</h3>
                    <p class="text-slate-400 text-sm">Dokumen atau surat diterima oleh bagian tata usaha RRI Batam.</p>
                </div>
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                    <div class="text-aris-500 font-black text-3xl mb-3">02</div>
                    <h3 class="font-bold mb-2">Input ke Sistem</h3>
                    <p class="text-slate-400 text-sm">Petugas mengunggah dan mencatat metadata dokumen ke website ARIS.</p>
                </div>
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                    <div class="text-aris-500 font-black text-3xl mb-3">03</div>
                    <h3 class="font-bold mb-2">Klasifikasi Otomatis</h3>
                    <p class="text-slate-400 text-sm">Sistem mengelompokkan arsip berdasarkan kategori dan unit kerja terkait.</p>
                </div>
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                    <div class="text-aris-500 font-black text-3xl mb-3">04</div>
                    <h3 class="font-bold mb-2">Penelusuran & Laporan</h3>
                    <p class="text-slate-400 text-sm">Arsip siap dicari, diakses, dan direkap kapan pun dibutuhkan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-slate-900 text-slate-400 py-8 text-center border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 flex flex-col items-center">
            <div class="flex items-center gap-2 mb-4 opacity-60 hover:opacity-100 transition-opacity">
                <div class="w-6 h-6 bg-white rounded-md flex items-center justify-center text-slate-900 font-black text-xs">RRI</div>
                <span class="font-bold text-white text-sm">Website Arsip ARIS - RRI Batam</span>
            </div>
            <p class="text-sm">
                &copy; {{ date('Y') }} RRI Stasiun Batam. Rancang Bangun Website Arsip Aris.
            </p>
        </div>
    </footer>

</body>
</html>