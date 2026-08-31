<?php
include dirname(__DIR__, 2) . '/koneksi.php';
include dirname(__DIR__, 2) . '/auth.php';
requireRole('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

mysqli_query($koneksi, "DELETE FROM aturan_pembayaran WHERE id=$id");

header("location:tampil.php");
