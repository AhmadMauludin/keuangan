<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id_siswa   = (int)$_POST['id_siswa'];
$id_aturan  = (int)$_POST['id_aturan'];
$nominal    = (int)$_POST['nominal_bayar'];
$metode     = mysqli_real_escape_string($koneksi, $_POST['metode_bayar']);
$tanggal    = mysqli_real_escape_string($koneksi, $_POST['tanggal_bayar']);
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

if (!$id_siswa || !$id_aturan || !$nominal || !$metode || !$tanggal) {
    header("location:bayar_langsung.php?error=1");
    exit;
}

// Check if already has pending/confirmed payment for this aturan
$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM pembayaran WHERE id_siswa=$id_siswa AND id_aturan=$id_aturan AND status IN ('pending','dikonfirmasi')"));
if ($cek['jml'] > 0) {
    header("location:bayar_langsung.php?error=sudah_bayar");
    exit;
}

$query = "INSERT INTO pembayaran (id_siswa, id_aturan, nominal_bayar, metode_bayar, bukti_bayar, keterangan, tanggal_bayar, status, tanggal_konfirmasi)
          VALUES ($id_siswa, $id_aturan, $nominal, '$metode', NULL, '$keterangan', '$tanggal', 'dikonfirmasi', NOW())";
mysqli_query($koneksi, $query);

header("location:konfirmasi.php?status=bayar_langsung");
