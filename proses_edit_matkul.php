<?php
include 'koneksi.php';

// Halaman ini hanya boleh diakses lewat form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Matkul.php');
    exit;
}

// Supaya query yang gagal mengembalikan false, sehingga blok else bisa jalan
mysqli_report(MYSQLI_REPORT_OFF);

$id          = (int) ($_POST['id'] ?? 0);
$kode        = trim($_POST['kode'] ?? '');
$nama_matkul = trim($_POST['nama_matkul'] ?? '');
$nama_dosen  = trim($_POST['nama_dosen'] ?? '');

if ($kode === '' || $nama_matkul === '' || $nama_dosen === '') {
    echo "<script>
        alert('Semua data wajib diisi');
        window.history.back();
    </script>";
    exit;
}

// Tanda ? diisi terpisah supaya aman dari SQL injection
$stmt  = mysqli_prepare($koneksi, "UPDATE mata_kuliah SET kode = ?, matkul = ?, dosen = ? WHERE id = ?");
$input = false;
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'sssi', $kode, $nama_matkul, $nama_dosen, $id);
    $input = mysqli_stmt_execute($stmt);
}

if ($input) {
    echo "<script>
        alert('Data Berhasil Diubah');
        window.location.href = 'Matkul.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal Mengubah Data');
        window.history.back();
    </script>";
}
?>