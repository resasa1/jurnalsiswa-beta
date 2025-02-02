<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "siswa") {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->prepare("DELETE FROM jurnal WHERE id = ? AND siswa_id = ?");
    $query->bind_param("ii", $id, $_SESSION["user_id"]);
    if ($query->execute()) {
        header("Location: index.php"); // Kembali ke dashboard setelah menghapus
        exit;
    } else {
        echo "Gagal menghapus jurnal.";
    }
} else {
    echo "ID jurnal tidak ditemukan.";
}
?>