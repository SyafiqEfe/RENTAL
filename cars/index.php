<?php
include '../db.php';

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mobil</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Daftar Mobil</h1>
    <a href="create.php">Tambah Mobil</a>
    <a href="../index.php">Dashboard</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Plat Nomor</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Tahun</th>
            <th>Harga per Hari</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['car_id'] ?></td>
            <td><?= $row['license_plate'] ?></td>
            <td><?= $row['brand'] ?></td>
            <td><?= $row['model'] ?></td>
            <td><?= $row['year'] ?></td>
            <td>Rp<?= number_format($row['price_per_day'], 2) ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td>
                <a href="update.php?id=<?= $row['car_id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $row['car_id'] ?>">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
