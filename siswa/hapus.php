<?php
include dirname(__DIR__) . '/koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM datasiswa WHERE id='$id'");
$siswa = mysqli_fetch_assoc($data);

if ($siswa['foto'] != "" && file_exists(BASE_PATH . '/uploads/foto_siswa/' . $siswa['foto'])) {
    unlink(BASE_PATH . '/uploads/foto_siswa/' . $siswa['foto']);
}

mysqli_query($koneksi, "DELETE FROM datasiswa WHERE id='$id'");

header("location:tampil.php");
