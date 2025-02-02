<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "siswa") {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->prepare("SELECT * FROM jurnal WHERE id = ? AND siswa_id = ?");
    $query->bind_param("ii", $id, $_SESSION["user_id"]);
    $query->execute();
    $result = $query->get_result();
    $jurnal = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST["judul"];
    $keterangan = $_POST["keterangan"];

    $query = $conn->prepare("UPDATE jurnal SET judul = ?, keterangan = ? WHERE id = ? AND siswa_id = ?");
    $query->bind_param("ssii", $judul, $keterangan, $id, $_SESSION["user_id"]);
    $query->execute();

    header("Location: index.php"); // Kembali ke dashboard setelah mengedit
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/editjurnal.css"> <!-- Link ke file CSS -->
    <title>Edit Jurnal</title>
</head>
<body>
    <div class="container">
        <h1>Edit Jurnal</h1>
        <form method="POST">
            <label for="judul">Judul:</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($jurnal['judul']); ?>" required>
            <label for="keterangan">Keterangan:</label>
            <textarea name="keterangan" required><?php echo htmlspecialchars($jurnal['keterangan']); ?></textarea>
            <button type="submit">Update</button>
            <a href="./index.php"><button class="gapsini">Kembali</button></a>
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aplikasi Jurnal Harian Siswa</p>
    </footer>
</body>
</html>