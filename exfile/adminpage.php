<?php
// Mulai session
session_start();

// GERBANG KEAMANAN: Cek jika user tidak login atau bukan admin, tendang ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginadmin.php");
    exit;
}

// Pengaturan untuk menampilkan tanggal dan waktu dalam format Indonesia
date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.utf8');

// Format tanggal dan waktu
$tanggal_sekarang = strftime('%A, %d %B %Y');
$waktu_sekarang = date('H:i:s') . ' WIB';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MA Hasan Munadi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 flex-shrink-0 bg-gray-800 text-gray-200 flex flex-col">
            <div class="p-5 flex items-center border-b border-gray-700">
                <img src="/image/logonav.png" class="h-8 w-auto mr-4">
                <span class="font-bold text-lg text-center">MA Hasan Munadi Admin Dashboard</span>
            </div>
            <div class="p-5 flex items-center bg-gray-700/50 border-b border-gray-700">
                <div class="w-12 h-12 rounded-full bg-teal-400 flex items-center justify-center mr-4 text-xl">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-sm"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></h4>
                    <p class="text-xs text-gray-400">@<?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
            </div>
            <ul class="flex-grow mt-5">
                <li><a href="#" class="flex items-center py-3 px-6 text-white bg-gray-700"><i class="fas fa-tachometer-alt w-6 text-center mr-3"></i><span>Dashboard</span></a></li>
                <li><a href="buatpengumuman.php" class="flex items-center py-3 px-6 text-gray-300 hover:bg-gray-700 hover:text-white"><i class="fas fa-bullhorn w-6 text-center mr-3"></i><span>Pengumuman</span></a></li>
                <li><a href="#" class="flex items-center py-3 px-6 text-gray-300 hover:bg-gray-700 hover:text-white"><i class="fas fa-cog w-6 text-center mr-3"></i><span>Pengaturan</span></a></li>
                <li class="mt-auto"><a href="logout.php" class="flex items-center py-3 px-6 text-gray-300 hover:bg-red-700 hover:text-white"><i class="fas fa-sign-out-alt w-6 text-center mr-3"></i><span>Logout</span></a></li>
            </ul>
        </aside>
        <main class="flex-1 flex flex-col">
            <header class="p-6 flex justify-between items-center bg-white border-b shadow-sm">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-semibold text-gray-700">Dasbor</h2>
                    <span class="text-sm text-gray-500 font-medium hidden md:block">
                        <?php echo $tanggal_sekarang; ?> | <?php echo $waktu_sekarang; ?>
                    </span>
                </div>
                <div class="flex items-center space-x-6 text-xl text-gray-600">
                    <i class="fas fa-bell cursor-pointer hover:text-gray-800"></i>
                    <i class="fas fa-user-circle cursor-pointer hover:text-gray-800"></i>
                </div>
            </header>
            <section class="flex-grow p-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold">Selamat Datang di Dashboard Admin!</h3>
                    <p class="mt-2 text-gray-600">Anda dapat mengelola konten website dari halaman ini.</p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>