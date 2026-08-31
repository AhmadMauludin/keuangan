<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireLogin();

$user = getCurrentUser();
$id_bayar = (int)($_POST['id_bayar'] ?? 0);
$metode     = mysqli_real_escape_string($koneksi, $_POST['metode_bayar'] ?? '');
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');
$tanggal    = date('Y-m-d');

if (!$id_bayar || !in_array($metode, ['cash', 'transfer', 'ewallet'])) {
    header("location:bayar.php");
    exit;
}

// Get pembayaran record
$bayar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id=$id_bayar"));
if (!$bayar) {
    header("location:bayar.php");
    exit;
}

// Verify ownership
if ($user['role'] === 'user' && $user['id_siswa'] && $user['id_siswa'] != $bayar['id_siswa']) {
    header("location:bayar.php");
    exit;
}

// Must be 'belum' or 'ditolak'
if (!in_array($bayar['status'], ['belum', 'ditolak'])) {
    header("location:bayar.php");
    exit;
}

$bukti = null;
if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['size'] > 0) {
    $file = $_FILES['bukti_bayar'];
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        header("location:bayar.php?id_bayar=$id_bayar&error=format");
        exit;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        header("location:bayar.php?id_bayar=$id_bayar&error=size");
        exit;
    }

    $filename = 'bukti_' . $bayar['id_siswa'] . '_' . time() . '.' . $ext;
    move_uploaded_file($file['tmp_name'], dirname(__DIR__) . '/uploads/bukti_bayar/' . $filename);
    $bukti = $filename;
}

// Update pembayaran: set metode, bukti, keterangan, status -> pending
$bukti_sql = $bukti ? "'$bukti'" : "NULL";
$nominal = (int)$bayar['nominal_bayar'] > 0 ? (int)$bayar['nominal_bayar'] : (int)$bayar['nominal_bayar'];

$query = "UPDATE pembayaran SET
          metode_bayar='$metode',
          bukti_bayar=$bukti_sql,
          keterangan='$keterangan',
          tanggal_bayar='$tanggal',
          status='pending'
          WHERE id=$id_bayar";
mysqli_query($koneksi, $query);

header("location:bayar.php?status=sukses");
