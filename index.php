<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pengaduan Sarana Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-content">
        <div class="logo-placeholder">LOGO</div>
        <div>
            <h1>Pengaduan Sarana Sekolah</h1>
            <p>Sampaikan aspirasi Anda untuk kemajuan sekolah kita</p>
        </div>
    </div>
</header>

<div class="container">
    <nav>
        <a href="index.php" class="active">Form Aspirasi</a>
        <a href="student_status.php">Cek Status & Histori</a>
        <a href="login.php" style="margin-left:auto;">Login Admin</a>
    </nav>
    
    <h2>Form Aspirasi Siswa</h2>
    <p style="margin-bottom: 20px; color: #666;">Silahkan isi form dibawah ini dengan data yang valid.</p>

    <!-- Success/Error Message Display -->
    <?php if(isset($_GET['status'])): ?>
        <?php if($_GET['status'] == 'success'): ?>
            <div class="alert alert-success">
                Laporan berhasil dikirim! Silahkan cek status secara berkala.
            </div>
        <?php elseif($_GET['status'] == 'error'): ?>
            <div class="alert alert-danger">
                Terjadi kesalahan saat mengirim laporan. Silahkan coba lagi.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form action="process_aspirasi.php" method="POST">
        <div class="form-group">
            <label for="nis">NIS (Nomor Induk Siswa)</label>
            <input type="number" id="nis" name="nis" class="form-control" placeholder="Masukkan NIS Anda" required>
        </div>

        <div class="form-group">
            <label for="kelas">Kelas</label>
            <!-- Assuming simple text input or predefined options. Using text for flexibility as per user schema 'kelas' -->
            <input type="text" id="kelas" name="kelas" class="form-control" placeholder="Contoh: XII IPA 1" required>
        </div>

        <div class="form-group">
            <label for="id_kategori">Kategori Pengaduan</label>
            <select name="id_kategori" id="id_kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                $query = mysqli_query($conn, "SELECT * FROM kategori");
                while($row = mysqli_fetch_array($query)) {
                    echo "<option value='".$row['id_kategori']."'>".$row['ket_kategori']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi Kejadian / Sarana</label>
            <input type="text" id="lokasi" name="lokasi" class="form-control" placeholder="Contoh: Ruang Kelas X-1, Kantin, Toilet Lt.2" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan / Isi Laporan</label>
            <textarea id="keterangan" name="keterangan" class="form-control" placeholder="Jelaskan detail pengaduan atau aspirasi Anda..." required></textarea>
        </div>

        <button type="submit" name="submit_aspirasi" class="btn btn-block">Kirim Aspirasi</button>
    </form>
</div>

<footer>
    &copy; <?php echo date('Y'); ?> Aplikasi Pengaduan Sarana Sekolah.
</footer>

</body>
</html>
