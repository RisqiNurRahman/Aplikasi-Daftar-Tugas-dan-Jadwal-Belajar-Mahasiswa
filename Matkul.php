<?php
include 'koneksi.php';

// Pencarian nama matkul (form di bawah memakai method GET)
$cari = trim($_GET["cari"] ?? "");
$kata = "%" . addcslashes($cari, "%_\\") . "%";

$stmt = mysqli_prepare($koneksi, "SELECT id, kode, matkul AS nama_matkul, dosen AS nama_dosen FROM mata_kuliah WHERE matkul LIKE ?");
mysqli_stmt_bind_param($stmt, "s", $kata);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TugasKu - Mata Kuliah</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    :root {
      --sidebar-width: 150px;
      --warna-utama: #0066CC;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: var(--warna-utama);
    }

    .sidebar .nav-link {
      color: #fff;
      border-radius: 0;
      padding: 0.5rem 1rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: rgba(255, 255, 255, 0.25);
      color: #fff;
    }

    .navbar-atas {
      margin-left: var(--sidebar-width);
      background: #dbeef8;
      border-bottom: 1px solid var(--warna-utama);
    }

    .konten {
      margin-left: var(--sidebar-width);
      padding: 20px 30px;
    }

    .modal-hapus {
      border: 2px solid #000;
      border-radius: 30px;
    }
  </style>
</head>
<body>

  <aside class="sidebar d-flex flex-column text-center">
    <h4 class="text-white py-4 mb-4">TugasKu</h4>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="Dasboardd.html">Beranda</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="Matkul.php" aria-current="page">Mata Kuliah</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Tugas.html">Tugas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Jadwal tugas.html">Jadwal</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="rekap.html">Rekap</a>
      </li>
    </ul>
  </aside>

  <nav class="navbar navbar-atas py-1">
    <div class="container-fluid justify-content-end">
      <span class="navbar-text small text-dark">
        <i class="bi bi-person-circle me-1"></i>Mahasiswa
      </span>
    </div>
  </nav>

  <main class="konten">

    <div class="d-flex justify-content-between align-items-start mb-3">
      <h2 class="fw-normal">Mata Kuliah</h2>
      <a href="tambah_matkul.php" class="btn btn-primary">+ Tambah Matkul</a>
    </div>

    <form class="d-flex mt-3 mb-4" role="search" method="GET" action="Matkul.php" style="max-width: 900px;">
      <input class="form-control me-2" type="search" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama Matkul..." aria-label="Cari matkul">
      <button class="btn btn-primary" type="submit" aria-label="Cari">
        <i class="bi bi-search"></i>
      </button>
    </form>

    <div class="container-fluid my-4 px-5">
      <table class="table table-bordered border-dark text-center align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Matkul</th>
            <th>Dosen</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($data) === 0): ?>
            <tr>
              <td colspan="5">Belum ada data mata kuliah.</td>
            </tr>
          <?php else: ?>
            <?php $no = 1; foreach ($data as $m): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($m["kode"]) ?></td>
                <td><?= htmlspecialchars($m["nama_matkul"]) ?></td>
                <td><?= htmlspecialchars($m["nama_dosen"]) ?></td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="edit_matkul.php?id=<?= (int) $m["id"] ?>" class="btn btn-secondary">Edit</a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="<?= (int) $m["id"] ?>">Hapus</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-hapus">
        <div class="modal-body text-center py-5 px-4">
          <h4 class="mb-4" id="judulHapus">Hapus Mata Kuliah</h4>
          <p class="small mb-4">Apa kamu yakin ingin menghapus mata kuliah ini?</p>

          <div class="d-flex justify-content-center gap-5">
            <button type="button" class="btn btn-light border border-dark btn-sm px-4" data-bs-dismiss="modal">Batal</button>
            <form action="hapus_matkul.php" method="POST">
              <input type="hidden" name="id" id="hapus-id">
              <button type="submit" class="btn btn-danger btn-sm px-4">Hapus</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Saat modal dibuka, isi id sesuai tombol Hapus yang diklik
    document.getElementById('modalHapus').addEventListener('show.bs.modal', function (event) {
      document.getElementById('hapus-id').value = event.relatedTarget.getAttribute('data-id');
    });
  </script>
</body>
</html>