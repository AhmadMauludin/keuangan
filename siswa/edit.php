<?php
include dirname(__DIR__) . '/koneksi.php';

$judul = 'Edit Data Siswa';

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM datasiswa WHERE id='$id'");
$siswa = mysqli_fetch_assoc($data);

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit Data Siswa</h2>

<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $siswa['id']; ?>">
    <input type="hidden" name="foto_lama" value="<?= $siswa['foto']; ?>">

    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($siswa['nama']); ?>" required><br>

    <label>Kelas:</label><br>
    <select name="kelas" required>
        <option value="X" <?= $siswa['kelas'] == 'X' ? 'selected' : ''; ?>>X</option>
        <option value="XI" <?= $siswa['kelas'] == 'XI' ? 'selected' : ''; ?>>XI</option>
        <option value="XII" <?= $siswa['kelas'] == 'XII' ? 'selected' : ''; ?>>XII</option>
    </select><br>

    <label>Tanggal Lahir:</label><br>
    <input type="date" name="tanggal_lahir" value="<?= $siswa['tanggal_lahir']; ?>" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" value="<?= htmlspecialchars($siswa['alamat']); ?>" required><br>

    <label>Foto Lama:</label><br>
    <img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $siswa['foto']; ?>" width="100"><br><br>

    <label>Ganti Foto (opsional):</label><br>
    <input type="file" name="foto"><br>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
