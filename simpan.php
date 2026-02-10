<?php
require 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// AMBIL DATA (SESUAI INDEX)
$nama       = trim($_POST['nama'] ?? '');
$kelas      = trim($_POST['kelas'] ?? '');
$keperluan  = $_POST['keperluan'] ?? '';
$judul_buku = trim($_POST['judul_buku'] ?? '');
$waktu      = date('Y-m-d H:i:s');

// ======================
// VALIDASI
// ======================
if ($nama=='' || $kelas=='' || $keperluan=='') {
    header("Location: index.php?error=lengkap");
    exit;
}

if (
    ($keperluan=='meminjam buku' || $keperluan=='mengembalikan buku')
    && $judul_buku==''
) {
    header("Location: index.php?error=buku");
    exit;
}

// ======================
// CEK SPAM (2 MENIT)
// ======================
$cek = mysqli_query($conn, "
    SELECT waktu FROM jurnal
    WHERE keperluan='$keperluan'
    AND IFNULL(judul_buku,'') = '$judul_buku'
    ORDER BY waktu DESC
    LIMIT 1
");

if ($r = mysqli_fetch_assoc($cek)) {
    if ((time() - strtotime($r['waktu'])) < 120) {
        header("Location: index.php?error=spam");
        exit;
    }
}

// ======================
// SIMPAN DATA
// ======================

if ($keperluan === 'menemui guru') {

    mysqli_query($conn, "
        INSERT INTO jurnal
        (nama, kelas, keperluan, waktu)
        VALUES
        ('$nama','$kelas','$keperluan','$waktu')
    ");

} elseif ($keperluan === 'meminjam buku') {

    mysqli_query($conn, "
        INSERT INTO jurnal
        (nama, kelas, keperluan, judul_buku, status_buku, waktu)
        VALUES
        ('$nama','$kelas','$keperluan','$judul_buku','dipinjam','$waktu')
    ");

} elseif ($keperluan === 'mengembalikan buku') {

    // 1️⃣ UPDATE STATUS PEMINJAMAN TERAKHIR
    mysqli_query($conn, "
        UPDATE jurnal SET
        status_buku = 'dikembalikan'
        WHERE judul_buku='$judul_buku'
        AND status_buku='dipinjam'
        ORDER BY waktu DESC
        LIMIT 1
    ");

    // 2️⃣ TAMBAH RIWAYAT PENGEMBALIAN (TANPA STATUS)
    mysqli_query($conn, "
        INSERT INTO jurnal
        (nama, kelas, keperluan, judul_buku, waktu)
        VALUES
        ('$nama','$kelas','$keperluan','$judul_buku','$waktu')
    ");
}


header("Location: sukses.php");
exit;