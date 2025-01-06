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
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['rental_id'] ?></td>
            <td><?= $row['customer_name'] ?></td>
            <td><?= $row['license_plate'] ?></td>
            <td><?= $row['rental_date'] ?></td>
            <td><?= $row['return_date'] ?: '-' ?></td>
            <td>Rp<?= number_format($row['total_price'], 2) ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td>
                <a href="update.php?id=<?= $row['rental_id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $row['rental_id'] ?>">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
