<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "guru") {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/admin.css"> <!-- Link ke file CSS -->
    <title>Dashboard Admin</title>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang, Guru!</h1>
        <nav>
            <a href="siswa.php">Kelola Siswa</a> | 
            <a href="jurnal.php">Daftar Jurnal</a> |
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aplikasi Jurnal Harian Siswa</p>
    </footer>
</body>
</html>