<?php
// Mulai sesi
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pengguna - Rental Mobil</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Selamat datang -->
    <h1>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>

    <!-- Navigasi -->
    <nav>
        <ul>
            <li><a href="rentals/index.php">Kelola Penyewaan</a></li>
            <li><a href="profile.php">Profil Saya</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Statistik Penyewaan -->
    <section>
        <h2>Statistik Penyewaan Anda</h2>
        <?php
        // Hitung jumlah penyewaan pengguna
        $user_id = $_SESSION['user_id'];
        $rental_count = $conn->query("SELECT COUNT(*) AS total FROM rentals WHERE customer_id = $user_id")->fetch_assoc()['total'];
        ?>
        <p>Total Penyewaan Anda: <?= $rental_count; ?></p>
    </section>

    <!-- Data Penyewaan Terbaru -->
    <section>
        <h2>Penyewaan Terbaru Anda</h2>
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
                $recent_rentals = $conn->query(
                    "SELECT rentals.rental_id, rentals.rental_date, rentals.status, cars.license_plate 
                     FROM rentals 
                     JOIN cars ON rentals.car_id = cars.car_id 
                     WHERE rentals.customer_id = $user_id 
                     ORDER BY rentals.rental_date DESC 
                     LIMIT 5"
                );

                if ($recent_rentals->num_rows > 0):
                    while ($rental = $recent_rentals->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $rental['rental_id']; ?></td>
                    <td><?= htmlspecialchars($rental['license_plate']); ?></td>
                    <td><?= htmlspecialchars($rental['rental_date']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($rental['status'])); ?></td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="4">Belum ada data penyewaan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</body>
</html>