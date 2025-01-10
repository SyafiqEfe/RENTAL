<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO customers (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php ");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Tambah Pelanggan</h1>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Telepon:</label>
        <input type="text" name="phone" required><br>
        <label>Alamat:</label>
        <input type="text" name="address" required><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
