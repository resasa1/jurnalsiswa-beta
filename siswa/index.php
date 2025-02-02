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

// Ambil data jurnal yang telah diupload oleh siswa
$query_jurnal = $conn->prepare("SELECT * FROM jurnal WHERE siswa_id = ?");
$query_jurnal->bind_param("i", $siswa_id);
$query_jurnal->execute();
$result_jurnal = $query_jurnal->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/siswa.css"> <!-- Link ke file CSS -->
    <title>Dashboard Siswa</title>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang, <?php echo htmlspecialchars($siswa["nama_lengkap"]); ?>!</h1>
        <nav>
            <a href="upload.php">Upload Jurnal</a> | 
            <a href="edit_profile.php">Edit Profil</a> | 
            <a href="../logout.php">Logout</a>
        </nav>

        <h2>Profil Siswa</h2>
        <div class="profil">
            <img src="../uploads/<?php echo htmlspecialchars($siswa['foto']); ?>" alt="Foto Profil" style="width: 100px; height: 100px; border-radius: 50%;">
            <p>Nama Lengkap: <?php echo htmlspecialchars($siswa["nama_lengkap"]); ?></p>
            <p>Username: <?php echo htmlspecialchars($siswa["username"]); ?></p>
            <p>Password: <em>********</em> (tidak ditampilkan untuk keamanan)</p>
        </div>

        <h2>Daftar Jurnal yang Telah Diupload</h2>
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Keterangan</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_jurnal->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["judul"]); ?></td>
                        <td><?php echo htmlspecialchars($row["keterangan"]); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($row["created_at"])); ?></td> <!-- Asumsi ada kolom created_at di tabel jurnal -->
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aplikasi Jurnal Harian Siswa</p>
    </footer>
</body>
</html>