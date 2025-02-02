<?php
session_start();
if (!isset($_SESSION["role"])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION["role"] == "siswa") {
    header("Location: siswa/index.php");
} else {
    header("Location: guru/index.php");
}
?>