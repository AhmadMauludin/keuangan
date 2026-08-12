<?php
include dirname(__DIR__) . '/koneksi.php';

$judul = 'Tambah Data Siswa';
include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Siswa</h2>

<form action="simpan.php" method="post" enctype="multipart/form-data">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br>

    <label>Kelas:</label><br>
    <select name="kelas" required>
        <option value="">-- Pilih Kelas --</option>
        <option value="X">X</option>
        <option value="XI">XI</option>
        <option value="XII">XII</option>
    </select><br>

    <label>Tanggal Lahir:</label><br>
    <input type="date" name="tanggal_lahir" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" required><br>

    <label>Foto:</label><br>
    <input type="file" name="foto" required><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
