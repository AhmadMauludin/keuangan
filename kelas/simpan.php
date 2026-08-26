<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$namakelas  = mysqli_real_escape_string($koneksi, $_POST['namakelas']);
$tingkat    = mysqli_real_escape_string($koneksi, $_POST['tingkat']);
$tahunajaran = mysqli_real_escape_string($koneksi, $_POST['tahunajaran']);
$idguru     = $_POST['idguru'] ? (int)$_POST['idguru'] : 'NULL';
$idsiswa    = $_POST['idsiswa'] ? (int)$_POST['idsiswa'] : 'NULL';
$idruang    = $_POST['idruang'] ? (int)$_POST['idruang'] : 'NULL';

$query = "INSERT INTO datakelas (namakelas, tingkat, tahunajaran, idguru, idsiswa, idruang) 
          VALUES ('$namakelas', '$tingkat', '$tahunajaran', $idguru, $idsiswa, $idruang)";

mysqli_query($koneksi, $query);

header("location:tampil.php");