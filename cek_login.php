<?php
session_start();
include 'config.php';

if(isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check admin table
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE user='$user' AND password='$password'");
    if(mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
    } else {
        header("Location: login.php?status=gagal");
        exit();
    }
}
?>