<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">

    <script>
        function toggleBuku() {
            const k = document.getElementById("keperluan").value;
            document.getElementById("buku-field").style.display =
                (k === "meminjam buku" || k === "mengembalikan buku")
                ? "block"
                : "none";
        }

        function disableButton() {
            const btn = document.querySelector("button[type='submit']");
            btn.disabled = true;
            btn.innerText = "Mengirim...";
        }
    </script>

    <style>
        .head {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .head p {
            padding-left: 10px;
            font-weight: bold;
        }
        img {
            width: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="head">
        <img src="assets/Logo-SMK-N-11-Smg-HD.png" alt="Logo SMKN N 11 Smg">
        <p>SMKN 11 SEMARANG</p>
    </div>

    <h2>Absensi Kunjungan Perpustakaan</h2>

    <!-- ERROR MESSAGE -->
    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;text-align:center;">
            <?php
            if ($_GET['error'] == 'lengkap') echo "⚠️ Semua data wajib diisi.";
            if ($_GET['error'] == 'buku') echo "⚠️ Nama buku wajib diisi.";
            if ($_GET['error'] == 'spam') echo "⚠️ Tunggu sebentar sebelum absen lagi.";
            ?>
        </p>
    <?php endif; ?>

    <form action="simpan.php" method="POST" onsubmit="disableButton()">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="text" name="kelas" placeholder="Kelas" required>

        <select name="keperluan" id="keperluan" onchange="toggleBuku()" required>
            <option value="">-- Pilih Keperluan --</option>
            <option value="menemui guru">Menemui Guru</option>
            <option value="meminjam buku">Meminjam Buku</option>
            <option value="mengembalikan buku">Mengembalikan Buku</option>
        </select>

        <div id="buku-field" style="display:none;">
            <input type="text" name="judul_buku" placeholder="Judul Buku">
        </div>

        <button type="submit">Absen Masuk</button>
    </form>
</div>

</body>
</html>
