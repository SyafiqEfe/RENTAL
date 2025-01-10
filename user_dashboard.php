<?php
// Mulai sesi
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
include 'db.php';

// Pastikan koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Proses form penyewaan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id']; // Ambil ID pelanggan dari form
    $car_id = $_POST['car_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];

    // Hitung jumlah hari sewa
    $rentalDate = new DateTime($rental_date);
    $returnDate = new DateTime($return_date);
    $interval = $rentalDate->diff($returnDate);
    $total_days = $interval->days;

    // Ambil harga per hari mobil
    $car_query = $conn->prepare("SELECT price_per_day FROM cars WHERE car_id = ?");
    $car_query->bind_param("i", $car_id);
    $car_query->execute();
    $car_result = $car_query->get_result();
    $car = $car_result->fetch_assoc();
    $price_per_day = $car['price_per_day'];

    // Hitung total harga
    $total_price = $total_days * $price_per_day;

    // Simpan penyewaan ke database
    $stmt = $conn->prepare("INSERT INTO rentals (customer_id, car_id, rental_date, return_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'ongoing')");
    $stmt->bind_param("iissd", $customer_id, $car_id, $rental_date, $return_date, $total_price);
    
    if ($stmt->execute()) {
        // Update status mobil ke "rented"
        $update_car_status = $conn->prepare("UPDATE cars SET status = 'rented' WHERE car_id = ?");
        $update_car_status->bind_param("i", $car_id);
        $update_car_status->execute();
        $update_car_status->close();
        
        echo "<script>alert('Penyewaan berhasil dibuat!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pengguna - Rental Mobil</title>
    <link rel="stylesheet" href="assets/style.css">
    <script>
        function calculateTotal() {
            const rentalDateInput = document.querySelector('input[name="rental_date"]');
            const returnDateInput = document.querySelector('input[name="return_date"]');
            const carSelect = document.querySelector('select[name="car_id"]');
            const totalDaysInput = document.querySelector('input[name="total_days"]');
            const totalPriceInput = document.querySelector('input[name="total_price"]');

            const rentalDate = new Date(rentalDateInput.value);
            const returnDate = new Date(returnDateInput.value);
            const pricePerDay = parseFloat(carSelect.options[carSelect.selectedIndex].getAttribute('data-price'));

            if (rentalDate && returnDate && returnDate >= rentalDate) {
                const timeDifference = returnDate - rentalDate;
                const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)); // Menghitung selisih hari

                totalDaysInput.value = dayDifference; // Menampilkan jumlah hari sewa
                totalPriceInput.value = (dayDifference * pricePerDay).toFixed(2); // Menghitung total harga
            } else {
                totalDaysInput.value = '';
                totalPriceInput.value = '';
            }
        }
    </script>
</head>
<body>
    <h1>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>

    <nav>
        <ul>
            <li><a href="profile.php">Profil Saya</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section>
        <h2>Buat Penyewaan Baru</h2>
        <form method="POST">
            <label for="customer_id">Pelanggan:</label>
            <select name="customer_id" id="customer_id" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php
                // Ambil data pelanggan
                $customers = $conn->query("SELECT * FROM customers");
                while ($customer = $customers->fetch_assoc()):
                ?>
                <option value="<?= $customer['customer_id']; ?>">
                    <?= htmlspecialchars($customer['name']); ?>
                </option>
                <?php endwhile; ?>
            </select>
            <br>

            <label for="car_id">Mobil:</label>
            <select name="car_id" id="car_id" required onchange="calculateTotal()">
                <option value="">-- Pilih Mobil --</option>
                <?php
                // Ambil mobil yang tersedia
                $cars = $conn->query("SELECT * FROM cars WHERE status = 'available'");
                while ($car = $cars->fetch_assoc()):
                ?>
                <option value="<?= $car['car_id']; ?>" data-price="<?= $car['price_per_day']; ?>">
                    <?= htmlspecialchars($car['license_plate']); ?> - Rp <?= number_format($car['price_per_day'], 2, ',', '.'); ?>/hari
                </option>
                <?php endwhile; ?>
            </select>
            <br>

            <label for="rental_date">Tanggal Sewa:</label>
            <input type="date" name="rental_date" required onchange="calculateTotal()">
            <br>

            <label for="return_date">Tanggal Kembali:</label>
            <input type="date" name="return_date" required onchange="calculateTotal()">
            <br>

            <label for="total_days">Total Hari:</label>
            <input type="text" name="total_days" readonly>
            <br>

            <label for="total_price">Total Harga:</label>
            <input type="text" name="total_price" readonly>
            <br>

            <button type="submit">Buat Penyewaan</button>
        </form>
    </section>

    <section>
        <h2>Penyewaan Terbaru Anda</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Penyewaan</th>
                    <th>Mobil</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $recent_rentals = $conn->query(
                    "SELECT rentals.rental_id, rentals.rental_date, rentals.return_date, rentals.total_price, rentals.status, 
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
                    <td><?= htmlspecialchars(($rental['total_price'])) ?></td>
                    <td><?= ucfirst(htmlspecialchars($rental['status'])); ?></td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="7">Belum ada data penyewaan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</body>
</html>