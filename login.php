<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki koneksi database di sini

// Cek apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa pengguna
    $stmt = $conn->prepare("SELECT user_id, username, role, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi kata sandi
        if (password_verify($password, $user['password'])) {
            // Simpan informasi pengguna ke dalam sesi
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan peran pengguna
            if ($user['role'] === 'admin') {
                header('Location: index.php'); // Halaman untuk admin
            } else {
                header('Location: user_dashboard.php'); // Halaman untuk pengguna biasa
            }
            exit;
        } else {
            $error = 'Kata sandi salah.';
        }
    } else {
        $error = 'Pengguna tidak ditemukan.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Login</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div>
            <label for="username">Nama Pengguna:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Kata Sandi:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a>.</p> <!-- Tautan ke halaman pendaftaran -->
</body>
</html>