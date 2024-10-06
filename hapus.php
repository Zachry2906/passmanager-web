<?php

require_once 'session.php';
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// SQL untuk menghapus data
$sql = "DELETE FROM passwords WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Menutup koneksi
$conn->close();
?>