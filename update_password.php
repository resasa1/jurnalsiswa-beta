<?php
include "config/database.php";

$username = "gojo"; // Sesuaikan username yang mau diperbarui
$new_password = "gojo"; // Masukkan password baru

$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

$query = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$query->bind_param("ss", $hashed_password, $username);
$query->execute();

echo "Password berhasil diperbarui!";
?>