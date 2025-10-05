<?php
// Mulai session
session_start();

// GERBANG KEAMANAN: Cek jika user tidak login atau bukan admin/pengawas, tendang ke halaman login
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'pengawas'])) {
    header("Location: loginadmin.php");
    exit;
}

require_once "../db/connection.php"; // Panggil file koneksi

$pesan_sukses = '';
$pesan_error = '';

// Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $target_penerima = $_POST['target_penerima'];
    $id_user_pembuat = $_SESSION['user_id']; // Ambil ID user dari session

    // Validasi sederhana
    if (empty($judul) || empty($isi)) {
        $pesan_error = "Judul dan Isi pengumuman tidak boleh kosong.";
    } else {
        try {
            // Siapkan query SQL untuk memasukkan data
            $sql = "INSERT INTO pengumuman (judul, isi, id_user_pembuat, target_penerima) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            // Eksekusi query dengan data dari form
            if ($stmt->execute([$judul, $isi, $id_user_pembuat, $target_penerima])) {
                $pesan_sukses = "Pengumuman berhasil dipublikasikan!";
                // Opsional: Arahkan ke halaman lain setelah sukses
                // header("Location: daftar_pengumuman.php?status=sukses");
                // exit;
            } else {
                $pesan_error = "Terjadi kesalahan saat menyimpan data.";
            }
        } catch (PDOException $e) {
            $pesan_error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengumuman - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 flex-shrink-0 bg-gray-800 text-gray-200 flex flex-col">
            <div class="p-5 flex items-center border-b border-gray-700">
                <img src="/image/logonav.png" class="h-8 w-auto mr-4">
                <span class="font-bold text-lg text-center">Admin Dashboard</span>
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
                <li><a href="adminpage.php" class="flex items-center py-3 px-6 text-gray-300 hover:bg-gray-700 hover:text-white"><i class="fas fa-tachometer-alt w-6 text-center mr-3"></i><span>Dashboard</span></a></li>
                <li><a href="buatpengumuman.php" class="flex items-center py-3 px-6 text-white bg-gray-700"><i class="fas fa-bullhorn w-6 text-center mr-3"></i><span>Pengumuman</span></a></li>
                <li><a href="#" class="flex items-center py-3 px-6 text-gray-300 hover:bg-gray-700 hover:text-white"><i class="fas fa-cog w-6 text-center mr-3"></i><span>Pengaturan</span></a></li>
                <li class="mt-auto"><a href="logout.php" class="flex items-center py-3 px-6 text-gray-300 hover:bg-red-700 hover:text-white"><i class="fas fa-sign-out-alt w-6 text-center mr-3"></i><span>Logout</span></a></li>
            </ul>
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="p-6 flex justify-between items-center bg-white border-b shadow-sm">
                <h2 class="text-2xl font-semibold text-gray-700">Buat Pengumuman Baru</h2>
            </header>
            
            <section class="flex-grow p-6">
                <div class="w-full max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
                    
                    <?php if (!empty($pesan_sukses)): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Sukses</p>
                            <p><?php echo $pesan_sukses; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($pesan_error)): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error</p>
                            <p><?php echo $pesan_error; ?></p>
                        </div>
                    <?php endif; ?>

                    <form action="buatpengumuman.php" method="POST" class="space-y-6">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman</label>
                            <input type="text" id="judul" name="judul" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="isi" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                            <textarea id="isi" name="isi" rows="10" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                        </div>
                        <div>
                            <label for="target_penerima" class="block text-sm font-medium text-gray-700 mb-1">Target Penerima</label>
                            <select id="target_penerima" name="target_penerima"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="semua">Semua</option>
                                <option value="guru">Hanya Guru</option>
                                <option value="siswa">Hanya Siswa</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200">
                                <i class="fas fa-paper-plane"></i>
                                Publikasikan
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>