<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Aspirasi - Aplikasi Pengaduan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-content">
        <div class="logo-placeholder">
            <img src="asset/logo.png" alt="Logo">
        </div>
        <div>
            <h1>Pengaduan Sarana Sekolah</h1>
            <p>Cek Status & Histori Laporan Anda</p>
        </div>
    </div>
</header>

<div class="container">
    <nav>
        <a href="index.php">Form Aspirasi</a>
        <a href="student_status.php" class="active">Cek Status & Histori</a>
         <a href="login.php" style="margin-left:auto;">Login Admin</a>
    </nav>
    
    <h2>Cek Progres Laporan</h2>
    <p style="margin-bottom: 20px; color: #666;">Masukkan NIS untuk melihat riwayat dan status pengaduan Anda.</p>

    <form action="" method="GET" style="margin-bottom: 30px;">
        <div class="form-group" style="display: flex; gap: 10px;">
            <input type="number" name="cari_nis" class="form-control" placeholder="Masukkan NIS Anda..." value="<?php echo isset($_GET['cari_nis']) ? $_GET['cari_nis'] : ''; ?>" required>
            <button type="submit" class="btn">Cari</button>
        </div>
    </form>

    <?php if(isset($_GET['cari_nis'])): ?>
        <?php
        $nis = mysqli_real_escape_string($conn, $_GET['cari_nis']);
        
        $query = "SELECT ia.*, a.status, a.feedback, k.ket_kategori 
                  FROM input_aspirasi ia
                  JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
                  JOIN kategori k ON ia.id_kategori = k.id_kategori
                  WHERE ia.nis = '$nis'
                  ORDER BY ia.tgl_lapor DESC";
        
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        ?>

        <?php if($count > 0): ?>
            <h3>Ditemukan <?php echo $count; ?> Riwayat Aspirasi</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Kategori</th>
                            <th width="30%">Keterangan & Lokasi</th>
                            <th width="10%">Status</th>
                            <th width="28%">Umpan Balik (Feedback)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['tgl_lapor']; ?></td>
                            <td><?php echo $row['ket_kategori']; ?></td>
                            <td>
                                <strong>Lokasi:</strong> <?php echo $row['lokasi']; ?><br>
                                <span style="font-size: 0.9em; color: #555;"><?php echo $row['keterangan']; ?></span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if(!empty($row['feedback'])): ?>
                                    <div style="background-color: #f9f9f9; padding: 8px; border-left: 3px solid #3498db; font-size: 0.9em;">
                                        <?php echo $row['feedback']; ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #999; font-style: italic;">Belum ada tanggapan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                Tidak ditemukan riwayat aspirasi untuk NIS <strong><?php echo $nis; ?></strong>.
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<footer>
    &copy; <?php echo date('Y'); ?> Aplikasi Pengaduan Sarana Sekolah.
</footer>

</body>
</html>
