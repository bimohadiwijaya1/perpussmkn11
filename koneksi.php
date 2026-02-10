<?php
$conn = mysqli_connect("localhost", "root", "", "db_perpus2");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
