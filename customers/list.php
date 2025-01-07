<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Koneksi ke database
include '../db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Penyewa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Daftar Penyewa</h1>
    <nav>
        <ul>
            <li><a href="../dashboard.php">Kembali ke Dashboard</a></li>
        </ul>
    </nav>

    <!-- Tabel Daftar Penyewa -->
    <table>
        <thead>
            <tr>
                <th>ID Penyewa</th>
                <th>Nama Penyewa</th>
                <th>Email</th>
                <th>Total Penyewaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil daftar penyewa dari database
            $users = $conn->query(
                "SELECT customers.customer_id, customers.name, customers.email, 
                        (SELECT COUNT(*) FROM rentals WHERE rentals.customer_id = customers.customer_id) AS rental_count 
                 FROM customers"
            );

            if ($users->num_rows > 0):
                while ($user = $users->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($user['customer_id']); ?></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= $user['rental_count']; ?></td>
                <td>
                    <a href="detail.php?id=<?= $user['customer_id']; ?>">Lihat Detail</a>
                </td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="5">Tidak ada data penyewa.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
