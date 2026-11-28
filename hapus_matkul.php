<?php
include 'koneksi.php';

// Hapus hanya boleh lewat tombol di modal (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Matkul.php');
    exit;
}

mysqli_report(MYSQLI_REPORT_OFF);

$id = (int) ($_POST['id'] ?? 0);

$stmt  = mysqli_prepare($koneksi, "DELETE FROM mata_kuliah WHERE id = ?");
$hapus = false;
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $hapus = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0;
}

$pesan = $hapus ? 'Data Berhasil Dihapus' : 'Gagal Menghapus Data';
echo "<script>
    alert('$pesan');
    window.location.href = 'Matkul.php';
</script>";
?>