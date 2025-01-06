<?php
include '../db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM rentals WHERE rental_id = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

$customers = $conn->query("SELECT * FROM customers");
$cars = $conn->query("SELECT * FROM cars");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $car_id = $_POST['car_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $sql = "UPDATE rentals SET 
            customer_id = '$customer_id',
            car_id = '$car_id',
            rental_date = '$rental_date',
            return_date = '$return_date',
            total_price = '$total_price',
            status = '$status'
            WHERE rental_id = $id";

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
    <title>Edit Penyewaan</title>
</head>
<body>
    <h1>Edit Penyewaan</h1>
    <form method="POST">
        <label>Pelanggan:</label>
        <select name="customer_id" required>
            <?php while ($customer = $customers->fetch_assoc()): ?>
            <option value="<?= $customer['customer_id'] ?>" <?= $data['customer_id'] == $customer['customer_id'] ? 'selected' : '' ?>>
                <?= $customer['name'] ?>
            </option>
            <?php endwhile; ?>
        </select><br>

        <label>Mobil:</label>
        <select name="car_id" required>
            <?php while ($car = $cars->fetch_assoc()): ?>
            <option value="<?= $car['car_id'] ?>" <?= $data['car_id'] == $car['car_id'] ? 'selected' : '' ?>>
                <?= $car['license_plate'] ?> - <?= $car['brand'] ?> <?= $car['model'] ?>
            </option>
            <?php endwhile; ?>
        </select><br>

        <label>Tanggal Sewa:</label>
        <input type="date" name="rental_date" value="<?= $data['rental_date'] ?>" required><br>

        <label>Tanggal Kembali:</label>
        <input type="date" name="return_date" value="<?= $data['return_date'] ?>"><br>

        <label>Total Harga:</label>
        <input type="number" name="total_price" value="<?= $data['total_price'] ?>" required><br>

        <label>Status:</label>
        <select name="status">
            <option value="ongoing" <?= $data['status'] == 'ongoing' ? 'selected' : '' ?>>Berlangsung</option>
            <option value="completed" <?= $data['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
            <option value="canceled" <?= $data['status'] == 'canceled' ? 'selected' : '' ?>>Dibatalkan</option>
        </select><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
