<?php
include '../db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM customers WHERE customer_id = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE customers SET name = '$name', email = '$email', phone = '$phone', address = '$address' WHERE customer_id = $id";
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
    <title>Edit Pelanggan</title>
</head>
<body>
    <h1>Edit Pelanggan</h1>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="name" value="<?= $data['name'] ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" required><br>
        <label>Telepon:</label>
        <input type="text" name="phone" value="<?= $data['phone'] ?>" required><br>
        <label>Alamat:</label>
        <textarea name="address" required><?= $data['address'] ?></textarea><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
