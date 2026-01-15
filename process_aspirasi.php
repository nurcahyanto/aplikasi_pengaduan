<?php
include 'config.php';

if(isset($_POST['submit_aspirasi'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    // Capture 'kelas' input to update/insert into siswa table if needed
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']); 
    
    $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tgl_lapor = date('Y-m-d');

    // 1. Handle Siswa Data
    // Check if siswa exists, if not insert, or update class if changed?
    // For simplicity given the requirement "input aspirasi", we'll ensure the NIS exists in our records 
    // or just insert/ignore.
    // Let's UPDATE or INSERT.
    $check_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
    if(mysqli_num_rows($check_siswa) > 0) {
        // Update class just in case they moved
        mysqli_query($conn, "UPDATE siswa SET kelas = '$kelas' WHERE nis = '$nis'");
    } else {
        // Insert new student
        mysqli_query($conn, "INSERT INTO siswa (nis, kelas) VALUES ('$nis', '$kelas')");
    }

    // 2. Insert into input_aspirasi
    $query_input = "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, keterangan, tgl_lapor) 
                    VALUES ('$nis', '$id_kategori', '$lokasi', '$keterangan', '$tgl_lapor')";
    
    if(mysqli_query($conn, $query_input)) {
        // Get the generated ID
        $id_pelaporan = mysqli_insert_id($conn);

        // 3. Initialize status in 'aspirasi' table
        // Status defaults to 'menunggu'
        $query_aspirasi = "INSERT INTO aspirasi (id_pelaporan, status) VALUES ('$id_pelaporan', 'menunggu')";
        
        if(mysqli_query($conn, $query_aspirasi)) {
            header("Location: index.php?status=success");
        } else {
            // Rollback text logic (delete input if aspirasi fails? optional but good)
             header("Location: index.php?status=error");
        }
    } else {
        header("Location: index.php?status=error");
    }
} else {
    header("Location: index.php");
}
?>
