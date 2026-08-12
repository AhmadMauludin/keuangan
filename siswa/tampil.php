<?php
include dirname(__DIR__) . '/koneksi.php';

$judul = 'Data Siswa';
$data = mysqli_query($koneksi, "SELECT * FROM datasiswa");
include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Data Siswa</h2>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Foto</th>
        <th>Aksi</th>
    </tr>

    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($data)) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['kelas']); ?></td>
            <td><?= htmlspecialchars($row['tanggal_lahir']); ?></td>
            <td><?= htmlspecialchars($row['alamat']); ?></td>
            <td>
                <img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $row['foto']; ?>" width="80">
            </td>
            <td>
                <a class="btn" href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                <a class="btn btn-danger" href="hapus.php?id=<?= $row['id']; ?>"
                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                    Hapus
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<br>
<a class="btn" href="index.php">Tambah Data</a>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
