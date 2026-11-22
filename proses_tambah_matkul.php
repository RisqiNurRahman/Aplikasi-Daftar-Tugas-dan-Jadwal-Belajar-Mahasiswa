<?php
// proses_tambah_matkul.php
// Menyimpan data matkul baru ke file matkul.json (tanpa database)

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tambah_matkul.php");
    exit;
}

// 1. Ambil data dari form
$kode        = trim($_POST["kode"] ?? "");
$nama_matkul = trim($_POST["nama_matkul"] ?? "");
$nama_dosen  = trim($_POST["nama_dosen"] ?? "");

// 2. Cek tidak boleh kosong
if ($kode === "" || $nama_matkul === "" || $nama_dosen === "") {
    header("Location: tambah_matkul.php?status=kosong");
    exit;
}

// 3. Baca data lama dari matkul.json (kalau file belum ada, mulai dari array kosong)
$file = "matkul.json";
$data = [];
if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data)) {
        $data = [];
    }
}

// 4. Tambahkan data baru ke array
$data[] = [
    "kode"        => $kode,
    "nama_matkul" => $nama_matkul,
    "nama_dosen"  => $nama_dosen,
];

// 5. Simpan kembali ke file
$hasil = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

// 6. Arahkan ke halaman daftar matkul
if ($hasil === false) {
    header("Location: tambah_matkul.php?status=gagal");
} else {
    header("Location: Matkul.php?status=tambah_sukses");
}
exit;