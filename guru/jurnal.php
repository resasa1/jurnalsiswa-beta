<?php
session_start();
include "../config/database.php";

if ($_SESSION["role"] != "guru") {
    header("Location: ../index.php");
    exit;
}

$result = $conn->query("SELECT jurnal.*, users.username FROM jurnal JOIN users ON jurnal.siswa_id = users.id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/jurnalguru.css"> <!-- Link ke file CSS -->
    <title>Daftar Jurnal</title>
</head>
<body>
    <div class="container">
        <h1>Daftar Jurnal Siswa</h1>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="jurnal-item">
                <h2>Siswa: <?php echo htmlspecialchars($row["username"]); ?></h2>
                <p>Judul: <?php echo htmlspecialchars($row["judul"]); ?></p>
                <p>Keterangan: <?php echo htmlspecialchars($row["keterangan"]); ?></p>
                <p>Tanggal Upload: <?php echo date("d-m-Y H:i:s", strtotime($row["created_at"])); ?></p> <!-- Menampilkan tanggal dan waktu -->
            </div>
            <hr>
        <?php endwhile; ?>
    </div>
</body>
</html>