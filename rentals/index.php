<?php
include '../db.php';

$sql = "SELECT rentals.*, customers.name AS customer_name, cars.license_plate 
        FROM rentals 
        JOIN customers ON rentals.customer_id = customers.customer_id 
        JOIN cars ON rentals.car_id = cars.car_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Penyewaan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Daftar Penyewaan</h1>
    <a href="create.php">Tambah Penyewaan</a>
    <a href="../index.php">Dashboard</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Pelanggan</th>
            <th>Plat Nomor Mobil</th>
            <th>Tanggal Sewa</th>
            <th>Tanggal Kembali</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['rental_id'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['license_plate']) ?></td>
                    <td><?= htmlspecialchars($row['rental_date']) ?></td>
                    <td><?= $row['return_date'] ? htmlspecialchars($row['return_date']) : '-' ?></td>
                    <td><?= htmlspecialchars($row['total_price']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                    <td>
                        <a href="update.php?id=<?= $row['rental_id'] ?>">Edit</a>
                        <a href="delete.php?id=<?= $row['rental_id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Tidak ada data penyewaan.</td>
            </tr>
        <?php endif; ?>
    </table>
    <script src="../assets/main.js"></script>
</html>