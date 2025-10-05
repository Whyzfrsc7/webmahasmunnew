<?php
require_once "../db/connection.php"; // Menggunakan require_once untuk file krusial

// Simulasi login, ganti ID untuk melihat sebagai user lain (1=admin, 2=pengawas, 3=siswa)
$current_user_id = 1; 

// Pastikan variabel koneksi (misalnya $conn) sudah ada dari connection.php
$stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$current_user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika user tidak ada, hentikan skrip
if (!$user) {
    die("User tidak ditemukan!");
}

$user_role = $user['role'];

// =================================================================
// LOGIKA UNTUK MENGAMBIL PENGUMUMAN
// =================================================================
if ($user_role === 'siswa') {
    $query = "SELECT p.*, u.nama_lengkap AS pembuat FROM pengumuman p 
              JOIN users u ON p.id_user_pembuat = u.id_user 
              WHERE p.target_penerima IN ('semua', 'siswa')
              ORDER BY p.tanggal_dibuat DESC";
} else {
    // Admin & Pengawas melihat semuanya
    $query = "SELECT p.*, u.nama_lengkap AS pembuat FROM pengumuman p 
              JOIN users u ON p.id_user_pembuat = u.id_user 
              ORDER BY p.tanggal_dibuat DESC";
}

$stmt = $conn->query($query);
$pengumuman_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script> <title>MA Hasan Munadi - Beji</title>
    <style>
        /* Sedikit custom style tambahan untuk pengumuman */
        .announcement-card {
            border-left: 5px solid #22c55e; /* Garis hijau di kiri */
        }
    </style>
</head>
<body class="bg-gray-50">
<nav class="bg-white shadow-md">
  <div class="mx-auto flex items-center justify-between p-4">
    <a href="#" class="flex items-center space-x-3">
      <img src="/image/logonav.png" class="h-12" alt="Logo">
      <div class="flex flex-col">
        <span class="text-xl font-bold">MA Hasan Munadi</span>
        <h2 class="text-xs font-medium">Lembaga Pendidikan Ma'Arif - Swasta</h2>
        <h3 class="text-xs font-medium">Kec. Beji, Gununggangsir, Banggle</h3>
      </div>
    </a>
    <button id="hamburgerBtn" class="md:hidden flex flex-col space-y-1.5 w-8 h-8 focus:outline-none">
      <span class="block h-1 w-full bg-black rounded"></span>
      <span class="block h-1 w-full bg-black rounded"></span>
      <span class="block h-1 w-full bg-black rounded"></span>
    </button>
    <ul class="hidden md:flex space-x-8 font-medium text-gray-900">
      <li><a href="#" class="hover:text-green-600">Beranda</a></li>
      <li class="relative group">
        <a href="#" class="hover:text-green-600">Informasi</a>
        <ul class="z-50 absolute left-0 mt-2 w-40 bg-white shadow-lg rounded-lg opacity-0 scale-y-0 origin-top transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:scale-y-100">
          <li><a href="#" class="block px-4 py-2 hover:bg-green-100">Pengumuman Sekolah</a></li>
          <li><a href="/exfile/beritapage.html" class="block px-4 py-2 hover:bg-green-100">Berita Sekolah</a></li>
          <li><a href="#" class="block px-4 py-2 hover:bg-green-100">Panduan Website</a></li>
          <li><a href="/exfile/brosur.html" class="block px-4 py-2 hover:bg-green-100">Brosur PPDB</a><li>
          <li><a href="https://hdmadrasah.id/login/auth" class="block px-4 py-2 hover:bg-green-100">RDM Login</a></li>
        </ul>
      </li>
      <li class="relative group">
        <a href="#" class="hover:text-green-600">Jadwal</a>
        <ul class="z-50 absolute left-0 mt-2 w-40 bg-white shadow-lg rounded-lg opacity-0 scale-y-0 origin-top transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:scale-y-100">
          <li><a href="#" class="block px-4 py-2 hover:bg-green-100">Mata Pelajaran</a></li>
          <li><a href="#" class="block px-4 py-2 hover:bg-green-100">Kegiatan Sekolah</a></li>
          <li><a href="/exfile/kaldikma.html" class="block px-4 py-2 hover:bg-green-100">Kalender Pendidikan</a></li>
          <li><a href="/exfile/jadwalujian.html" class="block px-4 py-2 hover:bg-green-100">Ujian UTS/UAS</a></li>
        </ul>
      </li>
      <li><a href="/exfile/kontak.html" class="hover:text-green-600">Kontak</a></li>
    </ul>

    <!-- Menu Login Desktop -->
    <div class="hidden md:block relative ml-33">
      <button id="loginBtn"
        class="flex items-center gap-2 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 
               text-black font-semibold rounded-lg shadow focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18 12h-9m0 0l3-3m-3 3l3 3" />
        </svg>
        Login
      </button>
      <ul id="loginDropdown"
          class="z-50 absolute right-0 mt-2 w-44 bg-white shadow-lg rounded-lg overflow-hidden
                 opacity-0 scale-y-0 origin-top transition-all duration-300 ease-in-out hidden z-50">
        <li><a href="/exfile/loginadmin.html" class="block px-4 py-2 hover:bg-yellow-100">Login Administrator</a></li>
        <li><a href="/exfile/loginpengawas.html" class="block px-4 py-2 hover:bg-yellow-100">Login Pengawas</a></li>
        <li><a href="/exfile/loginsiswa.html" class="block px-4 py-2 hover:bg-yellow-100">Login Siswa/Siswi</a></li>
      </ul>
    </div>
  </div>
</nav>
<div id="mobileMenu" class="md:hidden fixed top-0 right-0 h-full w-64 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
    <div class="p-4">
        <button id="closeBtn" class="float-right text-2xl leading-none">&times;</button>
        <div class="clear-both"></div>
    </div>
    <ul class="flex flex-col p-4 space-y-2 font-medium text-gray-900">
        <li><a href="#" class="block hover:text-green-600 py-2 px-4">Beranda</a></li>

        <li>
          <button class="w-full text-left hover:text-green-600 py-2 px-4 flex justify-between items-center dropdown-btn">
            Informasi
            <svg class="w-4 h-4 inline-block transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <ul class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out flex flex-col mt-1 space-y-1">
            <li><a href="#" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Pengumuman Sekolah</a></li>
            <li><a href="#" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Panduan Website</a></li>
            <li><a href="/exfile/brosur.html" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Brosur PPDB</a></li>
            <li><a href="https://hdmadrasah.id/login/auth" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">RDM Login</a></li>
          </ul>
        </li>
        <li>
            <button class="w-full text-left hover:text-green-600 py-2 px-4 flex justify-between items-center gap-2 dropdown-btn">
              Jadwal
              <svg class="w-4 h-4 inline-block transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            <ul class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out flex flex-col mt-1 space-y-1">
                <li><a href="#" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Mata Pelajaran</a></li>
                <li><a href="#" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Kegiatan Sekolah</a></li>
                <li><a href="/exfile/kaldikma.html" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Kalender Pendidikan</a></li>
                <li><a href="/exfile/jadwalujian.html" class="block hover:bg-green-100 rounded py-1 px-4 ml-4">Ujian UTS/UAS</a></li>
            </ul>
        </li>
        <li><a href="#" class="block hover:text-green-600 py-2 px-4">Kontak</a></li>
        <li class="mt-4">
            <button class="w-full text-left px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black rounded-lg dropdown-btn flex justify-between items-center">
              Login
              <svg class="w-4 h-4 inline-block transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            <ul class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out flex flex-col mt-1 space-y-1">
              <li><a href="/exfile/loginadmin.html" class="block px-4 py-2 hover:bg-yellow-100 ml-4">Login Administrator</a></li>
              <li><a href="/exfile/loginpengawas.html" class="block px-4 py-2 hover:bg-yellow-100 ml-4">Login Pengawas</a></li>
              <li><a href="/exfile/loginsiswa.html" class="block px-4 py-2 hover:bg-yellow-100 ml-4">Login Siswa/Siswi</a></li>
            </ul>
        </li>
    </ul>
</div>
<section class="hidden relative w-full h-screen overflow-hidden">
  <div id="slider" class="flex w-full h-full transition-transform duration-700">
    <div class="flex-shrink-0 w-full h-full">
      <img src="https://picsum.photos/id/1018/1600/900" 
           class="w-full h-full object-cover" alt="Slide 1">
    </div>
    <div class="flex-shrink-0 w-full h-full">
      <img src="https://picsum.photos/id/1015/1600/900" 
           class="w-full h-full object-cover" alt="Slide 2">
    </div>
    <div class="flex-shrink-0 w-full h-full">
      <img src="https://picsum.photos/id/1019/1600/900" 
           class="w-full h-full object-cover" alt="Slide 3">
    </div>
  </div>
<div class="absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-black/80 to-transparent pointer-events-none"></div>
  <div class="absolute inset-0 flex items-center justify-center text-center">
    <h1 id="fadeText" 
        class="text-white text-4xl md:text-6xl font-bold opacity-0 transition-opacity duration-1000">
      Welcome to MA Hasan Munadi
    </h1>
  </div>
  <button onclick="prevSlide()" 
          class="absolute top-1/2 left-4 -translate-y-1/2 bg-black/50 text-white p-3 rounded-full hover:bg-black/70"> 
    &#10094;  
  </button>
  <button onclick="nextSlide()" 
          class="absolute top-1/2 right-4 -translate-y-1/2 bg-black/50 text-white p-3 rounded-full hover:bg-black/70"> 
    &#10095; 
  </button>
</section>
<section class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div>
                <h2 class="text-3xl font-bold text-center text-gray-800">Papan Pengumuman</h2>
                <p class="text-center text-gray-600">Informasi terbaru dari sekolah untuk Anda.</p>
            </div>
        </div>

        <main>
            <?php if (count($pengumuman_list) > 0): ?>
                <div class="space-y-6">
                    <?php foreach ($pengumuman_list as $p): ?>
                        <div class="bg-white border shadow-lg rounded-lg p-6 announcement-card">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($p['judul']); ?></h3>
                                <span class="text-sm text-gray-500 bg-gray-200 px-3 py-1 rounded-full capitalize">
                                    <?php echo htmlspecialchars($p['target_penerima']); ?>
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 mb-4 border-b pb-2">
                                <span>Oleh: <strong><?php echo htmlspecialchars($p['pembuat']); ?></strong></span> |
                                <span><?php echo date('d F Y, H:i', strtotime($p['tanggal_dibuat'])); ?> WIB</span>
                            </div>
                            <div class="text-gray-700 leading-relaxed prose">
                                <?php echo nl2br(htmlspecialchars($p['isi'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded-lg">
                    <p class="font-bold">Informasi</p>
                    <p>Belum ada pengumuman yang dapat ditampilkan saat ini.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</section>
<script src="/js/script.js"></script>
<script src="/js/textslider.js"></script>
</body>
</html>