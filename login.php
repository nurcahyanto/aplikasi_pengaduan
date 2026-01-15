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
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Aplikasi Pengaduan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #ecf0f1; display: flex; align-items: center; justify-content: center; min-height: 100vh;">

<div class="container login-container" style="margin: 0;">
    <h2 style="text-align: center; margin-bottom: 20px;">Login Admin</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="user">Username</label>
            <input type="text" id="user" name="user" class="form-control" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-block">Masuk</button>
    </form>
    
    <div style="text-align: center; margin-top: 15px;">
        <a href="index.php">Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>
