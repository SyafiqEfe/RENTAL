<?php
// Mulai sesi
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Rental Mobil</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Selamat datang -->
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>

    <!-- Navigasi -->
    <nav>
        <ul>
            <li><a href="customers/index.php">Kelola Pelanggan</a></li>
            <li><a href="cars/index.php">Kelola Mobil</a></li>
            <li><a href="rentals/index.php">Kelola Penyewaan</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Statistik -->
    <section>
        <h2>Statistik</h2>
        <?php
        // Hitung jumlah pelanggan
        $customer_count = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];

        // Hitung jumlah mobil
        $car_count = $conn->query("SELECT COUNT(*) AS total FROM cars")->fetch_assoc()['total'];

        // Hitung jumlah penyewaan
        $rental_count = $conn->query("SELECT COUNT(*) AS total FROM rentals")->fetch_assoc()['total'];
        ?>
        <p>Total Pelanggan: <?= $customer_count; ?></p>
        <p>Total Mobil: <?= $car_count; ?></p>
        <p>Total Penyewaan: <?= $rental_count; ?></p>
    </section>

    <!-- Data terbaru -->
    <section>
        <h2>Data Terbaru</h2>
        <h3>5 Penyewaan Terbaru</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Mobil</th>
                    <th>Tanggal Sewa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent_rentals = $conn->query(
                    "SELECT rentals.rental_id, rentals.rental_date, rentals.status, 
                            customers.name AS customer_name, cars.license_plate 
                     FROM rentals 
                     JOIN customers ON rentals.customer_id = customers.customer_id 
                     JOIN cars ON rentals.car_id = cars.car_id 
                     ORDER BY rentals.rental_date DESC 
                     LIMIT 5"
                );

                if ($recent_rentals->num_rows > 0):
                    while ($rental = $recent_rentals->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $rental['rental_id']; ?></td>
                    <td><?= htmlspecialchars($rental['customer_name']); ?></td>
                    <td><?= htmlspecialchars($rental['license_plate']); ?></td>
                    <td><?= htmlspecialchars($rental['rental_date']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($rental['status'])); ?></td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="5">Belum ada data penyewaan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
