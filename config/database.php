<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'basisdata_final';

$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
