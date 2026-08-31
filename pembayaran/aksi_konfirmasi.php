<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$aksi = isset($_GET['aksi']) ? mysqli_real_escape_string($koneksi, $_GET['aksi']) : '';

if (!$id || !in_array($aksi, ['konfirmasi', 'tolak'])) {
    header("location:konfirmasi.php");
    exit;
}

$status = ($aksi === 'konfirmasi') ? 'dikonfirmasi' : 'ditolak';
$tanggal_konfirmasi = date('Y-m-d H:i:s');

$query = "UPDATE pembayaran SET
          status='$status',
          tanggal_konfirmasi='$tanggal_konfirmasi'
          WHERE id=$id AND status='pending'";

mysqli_query($koneksi, $query);

header("location:konfirmasi.php");
