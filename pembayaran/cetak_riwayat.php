<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala']);

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_status = isset($_GET['status_filter']) ? mysqli_real_escape_string($koneksi, $_GET['status_filter']) : '';
$filter_metode = isset($_GET['metode_filter']) ? mysqli_real_escape_string($koneksi, $_GET['metode_filter']) : '';
$tgl_dari = isset($_GET['tgl_dari']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_dari']) : '';
$tgl_sampai = isset($_GET['tgl_sampai']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_sampai']) : '';

$where = "WHERE 1=1";
if ($search) $where .= " AND (s.nama LIKE '%$search%' OR a.nama_biaya LIKE '%$search%')";
if ($filter_status) $where .= " AND p.status = '$filter_status'";
if ($filter_metode) $where .= " AND p.metode_bayar = '$filter_metode'";
if ($tgl_dari) $where .= " AND p.tanggal_bayar >= '$tgl_dari'";
if ($tgl_sampai) $where .= " AND p.tanggal_bayar <= '$tgl_sampai'";

$query = "SELECT p.*, s.nama as nama_siswa, k.namakelas, a.nama_biaya
          FROM pembayaran p
          LEFT JOIN datasiswa s ON p.id_siswa = s.id
          LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
          $where
          ORDER BY p.tanggal_bayar DESC, p.created_at DESC";
$data = mysqli_query($koneksi, $query);

$totalNominal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(p.nominal_bayar),0) AS total FROM pembayaran p LEFT JOIN datasiswa s ON p.id_siswa = s.id LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id $where"))['total'];

$metodeLabel = ['cash' => 'Cash', 'transfer' => 'Transfer', 'ewallet' => 'E-Wallet'];

$judul_cetak = "Riwayat Pembayaran";
if ($tgl_dari && $tgl_sampai) $judul_cetak .= " ($tgl_dari s/d $tgl_sampai)";
elseif ($tgl_dari) $judul_cetak .= " (dari $tgl_dari)";
elseif ($tgl_sampai) $judul_cetak .= " (s/d $tgl_sampai)";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul_cetak ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; background: #fff; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { font-size: 16px; }
        .header p { font-size: 11px; }
        h3 { font-size: 13px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 6px 8px; font-size: 11px; text-align: left; }
        th { background: #eee; font-weight: bold; }
        td.nominal { text-align: right; font-weight: bold; }
        th.nominal { text-align: right; }
        .total-bar { text-align: right; font-weight: bold; font-size: 13px; margin: 10px 0; }
        .info { font-size: 11px; color: #555; margin-bottom: 10px; }
        .footer { text-align: center; font-size: 10px; color: #888; margin-top: 20px; border-top: 1px solid #ccc; padding-top: 8px; }
        @media print { body { padding: 10px; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>SMA NUSANTARA</h2>
        <p>Jl. Pendidikan No. 123, Kota Bandung | Telp: (022) 1234567</p>
    </div>

    <h3><?= $judul_cetak ?></h3>
    <div class="info">
        Dicetak: <?= date('d/m/Y H:i') ?>
        <?php if ($search) echo " | Pencarian: " . htmlspecialchars($search); ?>
        <?php if ($filter_status) echo " | Status: " . ucfirst($filter_status); ?>
        <?php if ($filter_metode) echo " | Metode: " . ucfirst($filter_metode); ?>
    </div>

    <table>
        <tr>
            <th style="width:30px;">No</th>
            <th>Tgl Bayar</th>
            <th>Siswa</th>
            <th>Kelas</th>
            <th>Jenis Biaya</th>
            <th class="nominal">Nominal</th>
            <th>Metode</th>
            <th>Status</th>
        </tr>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal_bayar'])) ?></td>
                <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['namakelas'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_biaya'] ?? '-') ?></td>
                <td class="nominal">Rp <?= number_format($row['nominal_bayar'], 0, ',', '.') ?></td>
                <td><?= $metodeLabel[$row['metode_bayar']] ?? $row['metode_bayar'] ?></td>
                <td><?= ucfirst(str_replace('_', ' ', $row['status'])) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="total-bar">Total: Rp <?= number_format($totalNominal, 0, ',', '.') ?> (<?= $no - 1 ?> transaksi)</div>

    <div class="footer">Dokumen ini dicetak secara otomatis oleh Sistem Pembayaran SMA Nusantara</div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
