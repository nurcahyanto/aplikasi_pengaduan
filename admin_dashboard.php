<?php
session_start();
include 'config.php';

// Check login logic
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle Status & Feedback Update
if(isset($_POST['update_aspirasi'])) {
    $id_aspirasi = $_POST['id_aspirasi'];
    $status = $_POST['status'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    $query_update = "UPDATE aspirasi SET status='$status', feedback='$feedback' WHERE id_aspirasi='$id_aspirasi'";
    if(mysqli_query($conn, $query_update)) {
        $msg = "Aspirasi berhasil diperbarui.";
    } else {
        $error = "Gagal memperbarui aspirasi.";
    }
}

// Logout
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Sorting Logic
$orderBy = "ia.tgl_lapor DESC"; // Default
if(isset($_GET['sort'])) {
    $sort = $_GET['sort']; // safe enough for simple switch, or sanitize
    if($sort == 'date_asc') $orderBy = "ia.tgl_lapor ASC";
    elseif($sort == 'date_desc') $orderBy = "ia.tgl_lapor DESC";
    elseif($sort == 'kat_asc') $orderBy = "k.ket_kategori ASC";
    elseif($sort == 'siswa_asc') $orderBy = "ia.nis ASC";
}

// Fetch Data
$query = "SELECT ia.*, a.id_aspirasi, a.status, a.feedback, k.ket_kategori, s.kelas
          FROM input_aspirasi ia
          JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
          JOIN kategori k ON ia.id_kategori = k.id_kategori
          LEFT JOIN siswa s ON ia.nis = s.nis
          ORDER BY $orderBy";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aplikasi Pengaduan</title>
    <link rel="stylesheet" href="style.css">
    <script>
    function openModal(id, status, feedback) {
        document.getElementById('modalBackdrop').style.display = 'flex';
        document.getElementById('edit_id_aspirasi').value = id;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_feedback').value = feedback ? feedback : '';
    }
    function closeModal() {
        document.getElementById('modalBackdrop').style.display = 'none';
    }
    </script>
    <style>
        /* Simple Modal Styling */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }
        .filter-group {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<header>
    <div class="header-content">
        <div class="logo-placeholder">ADM</div>
        <div>
            <h1>Dashboard Admin</h1>
            <p>Halo, <?php echo $_SESSION['nama']; ?></p>
        </div>
    </div>
</header>

<div class="container" style="max-width: 1200px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Daftar Aspirasi Masuk</h2>
        <a href="?logout=true" class="btn" style="background-color: #e74c3c;">Logout</a>
    </div>

    <?php if(isset($msg)): ?> <div class="alert alert-success"><?php echo $msg; ?></div> <?php endif; ?>
    <?php if(isset($error)): ?> <div class="alert alert-danger"><?php echo $error; ?></div> <?php endif; ?>

    <div class="filter-group">
        <strong>Urutkan:</strong>
        <a href="?sort=date_desc" class="btn" style="padding: 5px 10px; font-size: 0.9em; background: #95a5a6;">Terbaru</a>
        <a href="?sort=date_asc" class="btn" style="padding: 5px 10px; font-size: 0.9em; background: #95a5a6;">Terlama</a>
        <a href="?sort=kat_asc" class="btn" style="padding: 5px 10px; font-size: 0.9em; background: #95a5a6;">Kategori</a>
        <a href="?sort=siswa_asc" class="btn" style="padding: 5px 10px; font-size: 0.9em; background: #95a5a6;">Siswa (NIS)</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tgl</th>
                    <th>NIS / Kelas</th>
                    <th>Kategori</th>
                    <th width="25%">Laporan & Lokasi</th>
                    <th>Status</th>
                    <th width="20%">Feedback</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['tgl_lapor']; ?></td>
                    <td>
                        <?php echo $row['nis']; ?><br>
                        <small><?php echo $row['kelas'] ? $row['kelas'] : '-'; ?></small>
                    </td>
                    <td><?php echo $row['ket_kategori']; ?></td>
                    <td>
                        <strong><?php echo $row['lokasi']; ?></strong><br>
                        <?php echo $row['keterangan']; ?>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $row['feedback']; ?></td>
                    <td>
                        <button class="btn" onclick="openModal('<?php echo $row['id_aspirasi']; ?>', '<?php echo $row['status']; ?>', '<?php echo htmlspecialchars($row['feedback'], ENT_QUOTES); ?>')" style="padding: 5px 10px; font-size: 0.9em;">
                            Tanggapi
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Edit/Feedback -->
<div id="modalBackdrop" class="modal-backdrop">
    <div class="modal-content">
        <h3>Berikan Tanggapan / Update Status</h3>
        <form action="" method="POST" style="margin-top: 15px;">
            <input type="hidden" id="edit_id_aspirasi" name="id_aspirasi">
            
            <div class="form-group">
                <label for="edit_status">Status</label>
                <select name="status" id="edit_status" class="form-control">
                    <option value="menunggu">Menunggu</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="edit_feedback">Umpan Balik (Feedback)</label>
                <textarea name="feedback" id="edit_feedback" class="form-control" placeholder="Tulis tanggapan untuk siswa..."></textarea>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn" style="background-color: #95a5a6;" onclick="closeModal()">Batal</button>
                <button type="submit" name="update_aspirasi" class="btn">Simpan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
