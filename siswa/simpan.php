<?php
include dirname(__DIR__) . '/koneksi.php';

$nama  = $_POST['nama'];
$kelas = $_POST['kelas'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$alamat = $_POST['alamat'];

$foto = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];

move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_siswa/' . $foto);

$query = "INSERT INTO datasiswa VALUES (NULL, '$nama', '$kelas','$tanggal_lahir','$alamat', '$foto')";
mysqli_query($koneksi, $query);

header("location:tampil.php");
