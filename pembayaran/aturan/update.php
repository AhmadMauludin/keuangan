<?php
include dirname(__DIR__, 2) . '/koneksi.php';
include dirname(__DIR__, 2) . '/auth.php';
requireRole('admin');

$id          = (int)$_POST['id'];
$nama_biaya  = mysqli_real_escape_string($koneksi, $_POST['nama_biaya']);
$nominal     = (int)$_POST['nominal'];
$keterangan  = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
$target      = mysqli_real_escape_string($koneksi, $_POST['target']);

$target_id = NULL;
if ($target === 'kelas' && isset($_POST['target_id_kelas']) && is_array($_POST['target_id_kelas'])) {
    $ids = array_map('intval', $_POST['target_id_kelas']);
    $target_id = implode(',', $ids);
} elseif ($target === 'tingkat' && isset($_POST['target_id_tingkat']) && is_array($_POST['target_id_tingkat'])) {
    $ids = array_map('intval', $_POST['target_id_tingkat']);
    $target_id = implode(',', $ids);
} elseif ($target === 'siswa' && isset($_POST['target_id_siswa']) && is_array($_POST['target_id_siswa'])) {
    $ids = array_map('intval', $_POST['target_id_siswa']);
    $target_id = implode(',', $ids);
}

$target_id_sql = $target_id !== NULL ? "'" . mysqli_real_escape_string($koneksi, $target_id) . "'" : "NULL";

$query = "UPDATE aturan_pembayaran SET
          nama_biaya='$nama_biaya',
          nominal=$nominal,
          keterangan='$keterangan',
          target='$target',
          target_id=$target_id_sql
          WHERE id='$id'";
mysqli_query($koneksi, $query);

// Sync pembayaran: remove records for students no longer matched, add for new matches
$tanggal = date('Y-m-d');

// Get all active student IDs that match the new target
$siswa_q = "SELECT id FROM datasiswa WHERE status='aktif'";
if ($target === 'kelas' && $target_id) {
    $kelas_ids = explode(',', $target_id);
    $siswa_q .= " AND id_kelas IN (" . implode(',', array_map('intval', $kelas_ids)) . ")";
} elseif ($target === 'tingkat' && $target_id) {
    $tingkat_ids = explode(',', $target_id);
    $tingkat_map = [10 => 'X', 11 => 'XI', 12 => 'XII'];
    $tingkat_vals = [];
    foreach ($tingkat_ids as $tid) {
        $tid = (int)$tid;
        if (isset($tingkat_map[$tid])) $tingkat_vals[] = "'" . $tingkat_map[$tid] . "'";
    }
    if ($tingkat_vals) {
        $siswa_q .= " AND id_kelas IN (SELECT id_kelas FROM datakelas WHERE tingkat IN (" . implode(',', $tingkat_vals) . "))";
    }
} elseif ($target === 'siswa' && $target_id) {
    $siswa_ids = explode(',', $target_id);
    $siswa_q .= " AND id IN (" . implode(',', array_map('intval', $siswa_ids)) . ")";
}

$matched_ids = [];
$result = mysqli_query($koneksi, $siswa_q);
while ($s = mysqli_fetch_assoc($result)) {
    $matched_ids[] = (int)$s['id'];
}

// Delete pembayaran 'belum' for students no longer in target
if ($matched_ids) {
    mysqli_query($koneksi, "DELETE FROM pembayaran WHERE id_aturan=$id AND status='belum' AND id_siswa NOT IN (" . implode(',', $matched_ids) . ")");
}

// Add pembayaran for new matched students
foreach ($matched_ids as $sid) {
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM pembayaran WHERE id_siswa=$sid AND id_aturan=$id"));
    if ($cek['jml'] == 0) {
        mysqli_query($koneksi, "INSERT INTO pembayaran (id_siswa, id_aturan, nominal_bayar, metode_bayar, bukti_bayar, keterangan, tanggal_bayar, status) VALUES ($sid, $id, $nominal, '', NULL, '', '$tanggal', 'belum')");
    }
}

header("location:tampil.php");
