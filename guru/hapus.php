<?php
session_start();
include "../config/database.php";

if ($_SESSION["role"] != "guru") {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'siswa'");
    $query->bind_param("i", $id);
    if ($query->execute()) {
        header("Location: siswa.php"); // Kembali ke halaman kelola siswa setelah menghapus
        exit;
    } else {
        echo "Gagal menghapus siswa.";
    }
} else {
    echo "ID siswa tidak ditemukan.";
}
?>