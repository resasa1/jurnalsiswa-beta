<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "siswa") {
    header("Location: ../index.php");
    exit;
}

// Ambil data siswa
$siswa_id = $_SESSION["user_id"];
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $siswa_id);
$query->execute();
$siswa = $query->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST["nama_lengkap"];
    $username = $_POST["username"];
    $foto = $_FILES["foto"]["name"];
    $foto_tmp = $_FILES["foto"]["tmp_name"];

    // Proses upload foto jika ada
    if ($foto) {
        move_uploaded_file($foto_tmp, "../uploads/" . $foto);
        $query = $conn->prepare("UPDATE users SET nama_lengkap = ?, username = ?, foto = ? WHERE id = ?");
        $query->bind_param("sssi", $nama_lengkap, $username, $foto, $siswa_id);
    } else {
        $query = $conn->prepare("UPDATE users SET nama_lengkap = ?, username = ? WHERE id = ?");
        $query->bind_param("ssi", $nama_lengkap, $username, $siswa_id);
    }
    
    $query->execute();
    echo "<p>Profil berhasil diperbarui!</p>";
}

// Tampilkan form edit
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/editprofile.css"> <!-- Link ke file CSS -->
    <title>Edit Profil</title>
</head>
<body>
    <div class="container">
        <h1>Edit Profil Siswa</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($siswa['nama_lengkap']); ?>" required>
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($siswa['username']); ?>" required>
            <label for="foto">Foto:</label>
            <input type="file" name="foto" accept="image/*">
            <button type="submit">Perbarui Profil</button>
        </form>
        <br>
        <a href="index.php"><button>Kembali ke Dashboard</button></a>
    </div>
</body>
</html>