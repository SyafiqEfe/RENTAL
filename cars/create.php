<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $license_plate = $_POST['license_plate'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price_per_day = $_POST['price_per_day'];
    $status = $_POST['status'];

    $sql = "INSERT INTO cars (license_plate, brand, model, year, price_per_day, status) 
            VALUES ('$license_plate', '$brand', '$model', '$year', '$price_per_day', '$status')";
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
    <title>Tambah Mobil</title>
</head>
<body>
    <h1>Tambah Mobil</h1>
    <form method="POST">
        <label>Plat Nomor:</label>
        <input type="text" name="license_plate" required><br>
        <label>Brand:</label>
        <input type="text" name="brand" required><br>
        <label>Model:</label>
        <input type="text" name="model" required><br>
        <label>Tahun:</label>
        <input type="number" name="year" required><br>
        <label>Harga per Hari:</label>
        <input type="number" name="price_per_day" required><br>
        <label>Status:</label>
        <select name="status">
            <option value="available">Tersedia</option>
            <option value="rented">Disewa</option>
            <option value="maintenance">Perbaikan</option>
        </select><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
