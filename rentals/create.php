<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $car_id = $_POST['car_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];
    $total_price = $_POST['total_price'];

    $sql = "INSERT INTO rentals (customer_id, car_id, rental_date, return_date, total_price, status) 
            VALUES ('$customer_id', '$car_id', '$rental_date', '$return_date', '$total_price', 'ongoing')";

    // Update status mobil ke "rented"
    $update_car_status = "UPDATE cars SET status = 'rented' WHERE car_id = '$car_id'";

    if ($conn->query($sql) === TRUE && $conn->query($update_car_status) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// Ambil data pelanggan dan mobil
$customers = $conn->query("SELECT * FROM customers");
$cars = $conn->query("SELECT * FROM cars WHERE status = 'available'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Penyewaan</title>
</head>
<body>
    <h1>Tambah Penyewaan</h1>
    <form method="POST">
        <label>Pelanggan:</label>
        <select name="customer_id" required>
            <option value="">-- Pilih Pelanggan --</option>
            <?php while ($customer = $customers->fetch_assoc()): ?>
            <option value="<?= $customer['customer_id'] ?>"><?= $customer['name'] ?></option>
            <?php endwhile; ?>
        </select><br>
        
        <label>Mobil:</label>
        <select name="car_id" required>
            <option value="">-- Pilih Mobil --</option>
            <?php while ($car = $cars->fetch_assoc()): ?>
            <option value="<?= $car['car_id'] ?>"><?= $car['license_plate'] ?> - <?= $car['brand'] ?> <?= $car['model'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Tanggal Sewa:</label>
        <input type="date" name="rental_date" required><br>

        <label>Tanggal Kembali:</label>
        <input type="date" name="return_date"><br>

        <label>Total Harga:</label>
        <input type="number" name="total_price" required><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
