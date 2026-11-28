<?php
include 'koneksi.php';

// Halaman ini hanya boleh diakses lewat form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah_matkul.php');
    exit;
}

// Supaya query yang gagal mengembalikan false, sehingga blok else bisa jalan
mysqli_report(MYSQLI_REPORT_OFF);

$kode   = trim($_POST['kode'] ?? '');
$matkul = trim($_POST['nama_matkul'] ?? '');
$dosen  = trim($_POST['nama_dosen'] ?? '');

if ($kode === '' || $matkul === '' || $dosen === '') {
    echo "<script>
        alert('Semua data wajib diisi');
        window.location.href = 'tambah_matkul.php';
    </script>";
    exit;
}

// Tanda ? diisi terpisah supaya aman dari SQL injection
$stmt  = mysqli_prepare($koneksi, "INSERT INTO mata_kuliah (kode, matkul, dosen) VALUES (?, ?, ?)");
$input = false;
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'sss', $kode, $matkul, $dosen);
    $input = mysqli_stmt_execute($stmt);
}

if ($input) {
    echo "<script>
        alert('Data Berhasil Disimpan');
        window.location.href = 'Matkul.php';
    </script>";
} else {
    die(mysqli_error($koneksi));
    echo "<script>  
        alert('Gagal Menyimpan Data');
        window.location.href = 'tambah_matkul.php';
    </script>";
}
?>
