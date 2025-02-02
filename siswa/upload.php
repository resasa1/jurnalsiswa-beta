<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "siswa") {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST["judul"];
    $keterangan = $_POST["keterangan"];
    $siswa_id = $_SESSION["user_id"];

    // Simpan ke database
    $query = $conn->prepare("INSERT INTO jurnal (siswa_id, judul, keterangan) VALUES (?, ?, ?)");
    $query->bind_param("iss", $siswa_id, $judul, $keterangan);
    $query->execute();

    echo "<p>Jurnal berhasil diupload!</p>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/upjurnal.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Jurnal</title>
</head>
<body>
    <h1>Upload Jurnal</h1>
    <form method="POST">
        <label for="judul">Judul:</label>
        <input type="text" name="judul" required><br>
        <label for="keterangan">Keterangan:</label>
        <textarea name="keterangan" required></textarea><br>
        <button type="submit">Upload</button>
    </form>
    
    <br>
    <a href="index.php"><button>Kembali ke Dashboard</button></a>
</body>
</html>