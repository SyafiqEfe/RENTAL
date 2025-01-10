<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $car_id = $_POST['car_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];
    $total_price = $_POST['total_price']; // Pastikan ini diambil dari form

    $sql = "INSERT INTO rentals (customer_id, car_id, rental_date, return_date, total_price, status) 
            VALUES ('$customer_id', '$car_id', '$rental_date', '$return_date', '$total_price', 'ongoing')";

    // Update status mobil ke "rented"
    $update_car_status = "UPDATE cars SET status = 'rented' WHERE car_id = '$car_id'";

    if ($conn->query($sql) === TRUE && $conn->query($update_car_status) === TRUE) {
        header("Location: index.php"); // Redirect ke halaman daftar penyewaan
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
    <link rel="stylesheet" href="../assets/style.css">
    <script>
        function calculateRentalDays() {
            const rentalDateInput = document.querySelector('input[name="rental_date"]');
            const returnDateInput = document.querySelector('input[name="return_date"]');
            const rentalDaysInput = document.querySelector('input[name="rental_days"]');

            const rentalDate = new Date(rentalDateInput.value);
            const returnDate = new Date(returnDateInput.value);

            // Pastikan kedua tanggal valid
            if (!isNaN(rentalDate) && !isNaN(returnDate)) {
                const timeDifference = returnDate - rentalDate;
                const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)); // Menghitung selisih hari

                if (dayDifference >= 0) {
                    rentalDaysInput.value = dayDifference; // Menampilkan jumlah hari sewa
                    // Hitung total harga
                    const selectedCar = document.querySelector('#car_id option:checked');
                    const pricePerDay = selectedCar ? parseFloat(selectedCar.getAttribute('data-price')) : 0;
                    document.getElementById('total_price').value = (dayDifference * pricePerDay).toFixed(2);
                } else {
                    rentalDaysInput.value = ''; // Kosongkan jika tanggal kembali lebih awal
                }
            } else {
                rentalDaysInput.value = ''; // Kosongkan jika salah satu tanggal tidak valid
            }
        }
    </script>
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
        <select name="car_id" id="car_id" required>
            <option value="">-- Pilih Mobil --</option>
            <?php while ($car = $cars->fetch_assoc()): ?>
            <option value="<?= $car['car_id'] ?>" data-price="<?= $car['price_per_day'] ?>">
                <?= $car['license_plate'] ?> - <?= $car['brand'] ?> <?= $car['model'] ?>
            </option>
 <?php endwhile; ?>
        </select><br>

        <label>Tanggal Sewa:</label>
        <input type="date" name="rental_date" required onchange="calculateRentalDays()"><br>

        <label>Tanggal Kembali:</label>
        <input type="date" name="return_date" onchange="calculateRentalDays()"><br>

        <label>Jumlah Hari Sewa:</label>
        <input type="number" name="rental_days" required readonly><br>

        <label>Total Harga:</label>
        <input type="text" name="total_price" id="total_price" readonly required><br>

        <button type="submit">Simpan</button>
    </form>
    <script src="../assets/main.js"></script>
</body>
</html>