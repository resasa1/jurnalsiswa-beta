<?php
session_start();
include "../config/database.php";

if ($_SESSION["role"] != "guru") {
    header("Location: ../index.php");
    exit;
}

// Menampilkan daftar siswa
$result = $conn->query("SELECT * FROM users WHERE role='siswa'");

// Menambahkan siswa baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $nama_lengkap = $_POST["nama_lengkap"];
    
    // Proses upload foto
    $foto = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = $_FILES["foto"]["name"];
        $foto_tmp = $_FILES["foto"]["tmp_name"];
        move_uploaded_file($foto_tmp, "../uploads/" . $foto);
    }

    $query = $conn->prepare("INSERT INTO users (username, password, nama_lengkap, foto, role) VALUES (?, ?, ?, ?, 'siswa')");
    $query->bind_param("ssss", $username, $password, $nama_lengkap, $foto);
    $query->execute();
    echo "<p>Siswa ditambahkan!</p>";
    // Refresh daftar siswa setelah menambah siswa baru
    $result = $conn->query("SELECT * FROM users WHERE role='siswa'");
}

// Mengedit siswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $id = $_POST["id"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $nama_lengkap = $_POST["nama_lengkap"];
    
    // Proses upload foto
    $foto = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = $_FILES["foto"]["name"];
        $foto_tmp = $_FILES["foto"]["tmp_name"];
        move_uploaded_file($foto_tmp, "../uploads/" . $foto);
    }

    $query = $conn->prepare("UPDATE users SET username = ?, password = ?, nama_lengkap = ?, foto = ? WHERE id = ? AND role = 'siswa'");
    $query->bind_param("ssssi", $username, $password, $nama_lengkap, $foto, $id);
    $query->execute();
    echo "<p>Siswa diupdate!</p>";
    // Refresh daftar siswa setelah mengedit
    $result = $conn->query("SELECT * FROM users WHERE role='siswa'");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/addsiswa.css"> <!-- Link ke file CSS -->
    <title>Kelola Siswa</title>
</head>
<body>
    <div class="container">
        <h1>Kelola Siswa</h1>

        <h2>Tambah Siswa Baru</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" required>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="foto">Foto:</label>
            <input type="file" name="foto" accept="image/*" required>
            <button type="submit" name="add">Tambah Siswa</button>
        </form>

        <h2>Daftar Siswa</h2>
        <table>
            <thead>
                <tr>
                    <th>Foto Profil</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../uploads/<?php echo htmlspecialchars($row["foto"]); ?>" alt="Foto Profil" width="50" height="50"></td>
                        <td><?php echo htmlspecialchars($row["nama_lengkap"]); ?></td>
                        <td><?php echo htmlspecialchars($row["username"]); ?></td>
                        <td>
                            <a href='hapus.php?id=<?php echo $row["id"]; ?>'>Hapus</a> | 
                            <a href="#" onclick="editStudent(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['username']); ?>', '<?php echo htmlspecialchars($row['nama_lengkap']); ?>')">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Form untuk mengedit siswa -->
        <h2>Edit Siswa</h2>
        <form id="editForm" method="POST" enctype="multipart/form-data" style="display:none;">
            <input type="hidden" name="id" id="editId">
            <label for="editNamaLengkap">Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" id="editNamaLengkap" required>
            <label for="editUsername">Username:</label>
            <input type="text" name="username" id="editUsername" required>
            <label for="editPassword">Password:</label>
            <input type="password" name="password" id="editPassword" required>
            <label for="editFoto">Foto:</label>
            <input type="file" name="foto" accept="image/*">
            <button type="submit" name="edit">Update Siswa</button>
        </form>

        <!-- Tombol Kembali ke Dashboard -->
        <a href="index.php" class="back-button">Kembali ke Dashboard</a>
    </div>

    <script>
        function editStudent(id, username, nama_lengkap) {
            document.getElementById('editId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editNamaLengkap').value = nama_lengkap;
            document.getElementById('editForm').style.display = 'block';
        }
    </script>
</body>
</html>