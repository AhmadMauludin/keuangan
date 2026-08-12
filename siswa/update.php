<?php
include dirname(__DIR__) . '/koneksi.php';

$id         = $_POST['id'];
$nama       = $_POST['nama'];
$kelas      = $_POST['kelas'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$alamat     = $_POST['alamat'];
$foto_lama  = $_POST['foto_lama'];

$foto_baru = $_FILES['foto']['name'];
$tmp       = $_FILES['foto']['tmp_name'];

if ($foto_baru != "") {
    if (file_exists(BASE_PATH . '/uploads/foto_siswa/' . $foto_lama)) {
        unlink(BASE_PATH . '/uploads/foto_siswa/' . $foto_lama);
    }

    move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_siswa/' . $foto_baru);

    $query = "UPDATE datasiswa SET
              nama='$nama',
              kelas='$kelas',
              tanggal_lahir='$tanggal_lahir',
              alamat='$alamat',
              foto='$foto_baru'
              WHERE id='$id'";
} else {
    $query = "UPDATE datasiswa SET
              nama='$nama',
              kelas='$kelas',
              tanggal_lahir='$tanggal_lahir',
              alamat='$alamat'
              WHERE id='$id'";
}

mysqli_query($koneksi, $query);
header("location:tampil.php");
