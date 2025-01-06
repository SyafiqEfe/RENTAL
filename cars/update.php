<?php
include '../db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM cars WHERE car_id = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $license_plate = $_POST['license_plate'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price_per_day = $_POST['price_per_day'];
    $status = $_POST['status'];

    $sql = "UPDATE cars SET 
            license_plate = '$license_plate',
            brand = '$brand',
            model = '$model',
            year = '$year',
            price_per_day = '$price_per_day',
            status = '$status'
            WHERE car_id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Mobil</title>
</head>
<body>
    <h1>Edit Mobil</h1>
    <form method="POST">
        <label>Plat Nomor:</label>
        <input type="text" name="license_plate" value="<?= $data['license_plate'] ?>" required><br>
        <label>Brand:</label>
        <input type="text" name="brand" value="<?= $data['brand'] ?>" required><br>
        <label>Model:</label>
        <input type="text" name="model" value="<?= $data['model'] ?>" required><br>
        <label>Tahun:</label>
        <input type="number" name="year" value="<?= $data['year'] ?>" required><br>
        <label>Harga per Hari:</label>
        <input type="number" name="price_per_day" value="<?= $data['price_per_day'] ?>" required><br>
        <label>Status:</label>
        <select name="status">
            <option value="available" <?= $data['status'] == 'available' ? 'selected' : '' ?>>Tersedia</option>
            <option value="rented" <?= $data['status'] == 'rented' ? 'selected' : '' ?>>Disewa</option>
            <option value="maintenance" <?= $data['status'] == 'maintenance' ? 'selected' : '' ?>>Perbaikan</option>
        </select><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
