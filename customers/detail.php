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

// Ambil ID penyewa dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: list.php');
    exit;
}
$customer_id = intval($_GET['id']);

// Ambil data penyewa
$customer = $conn->query("SELECT * FROM customers WHERE customer_id = $customer_id")->fetch_assoc();
if (!$customer) {
    header('Location: list.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Penyewa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Detail Penyewa</h1>
    <nav>
        <ul>
            <li><a href="list.php">Kembali ke Daftar Penyewa</a></li>
        </ul>
    </nav>

    <!-- Informasi Penyewa -->
    <h2>Informasi Penyewa</h2>
    <p><strong>Nama:</strong> <?= htmlspecialchars($customer['name']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']); ?></p>

    <!-- Penyewaan -->
    <h2>Data Penyewaan</h2>
    <table>
        <thead>
            <tr>
                <th>ID Penyewaan</th>
                <th>Mobil</th>
                <th>Tanggal Sewa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rentals = $conn->query(
                "SELECT rentals.rental_id, rentals.rental_date, rentals.status, cars.license_plate 
                 FROM rentals 
                 JOIN cars ON rentals.car_id = cars.car_id 
                 WHERE rentals.customer_id = $customer_id 
                 ORDER BY rentals.rental_date DESC"
            );

            if ($rentals->num_rows > 0):
                while ($rental = $rentals->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($rental['rental_id']); ?></td>
                <td><?= htmlspecialchars($rental['license_plate']); ?></td>
                <td><?= htmlspecialchars($rental['rental_date']); ?></td>
                <td><?= ucfirst(htmlspecialchars($rental['status'])); ?></td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="4">Tidak ada data penyewaan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
