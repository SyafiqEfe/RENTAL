<?php
include '../db.php';

$id = $_GET['id'];
$sql = "DELETE FROM cars WHERE car_id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
