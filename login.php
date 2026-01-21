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
    
    <?php 
    if(isset($_GET['status'])) {
        if($_GET['status'] == 'gagal') {
            echo '<div class="alert alert-danger">Username atau Password salah!</div>';
        }
    }
    ?>

    <form action="cek_login.php" method="POST">
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
