<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireLogin();

$judul = 'Detail Pembayaran';
$user = getCurrentUser();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT p.*, s.nama as nama_siswa, s.alamat as alamat_siswa, k.namakelas,
          a.nama_biaya, a.nominal as nominal_aturan, a.keterangan as keterangan_biaya
          FROM pembayaran p
          LEFT JOIN datasiswa s ON p.id_siswa = s.id
          LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
          WHERE p.id = $id";
$data = mysqli_query($koneksi, $query);
$pembayaran = mysqli_fetch_assoc($data);

if (!$pembayaran) {
    header("location:" . ($user['role'] === 'admin' ? '../../pembayaran/konfirmasi.php' : '../../pembayaran/bayar.php'));
    exit;
}

// Check access: admin/kepala can see all, user can only see their own
if ($user['role'] === 'user' && $user['id_siswa'] && $user['id_siswa'] != $pembayaran['id_siswa']) {
    header("location:" . BASE_URL . "/pembayaran/bayar.php");
    exit;
}

$statusColor = [
    'belum' => '#95a5a6',
    'pending' => '#f39c12',
    'dikonfirmasi' => '#27ae60',
    'ditolak' => '#e74c3c'
];
$metodeLabel = [
    'cash' => 'Cash (Tunai)',
    'transfer' => 'Transfer Bank',
    'ewallet' => 'E-Wallet'
];

// Determine back URL
$backUrl = BASE_URL . '/pembayaran/bayar.php';
if (hasRole(['admin', 'kepala'])) {
    $backUrl = BASE_URL . '/pembayaran/konfirmasi.php';
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Detail Pembayaran</h2>

<div style="display: flex; gap: 30px; flex-wrap: wrap;">
    <div style="flex: 2; min-width: 300px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="padding:10px; font-weight:bold; width:200px; background:#f8f9fa;">No. Pembayaran</td>
                <td style="padding:10px;">#<?= str_pad($pembayaran['id'], 6, '0', STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Tanggal Bayar</td>
                <td style="padding:10px;"><?= date('d/m/Y', strtotime($pembayaran['tanggal_bayar'])) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Nama Siswa</td>
                <td style="padding:10px;"><?= htmlspecialchars($pembayaran['nama_siswa'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Kelas</td>
                <td style="padding:10px;"><?= htmlspecialchars($pembayaran['namakelas'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Jenis Biaya</td>
                <td style="padding:10px;"><?= htmlspecialchars($pembayaran['nama_biaya'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Nominal Dibayar</td>
                <td style="padding:10px; font-weight:bold; font-size:16px; color:#27ae60;">Rp <?= number_format($pembayaran['nominal_bayar'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Metode Pembayaran</td>
                <td style="padding:10px;">
                    <span style="padding:4px 10px; border-radius:4px; background:#3498db; color:#fff; font-size:13px;">
                        <?= $metodeLabel[$pembayaran['metode_bayar']] ?? $pembayaran['metode_bayar'] ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Status</td>
                <td style="padding:10px;">
                    <span style="padding:5px 12px; border-radius:4px; background:<?= $statusColor[$pembayaran['status']] ?>; color:#fff;">
                        <?= ucfirst(str_replace('_', ' ', $pembayaran['status'])) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Keterangan</td>
                <td style="padding:10px;"><?= htmlspecialchars($pembayaran['keterangan'] ?? '-') ?></td>
            </tr>
            <?php if ($pembayaran['catatan_admin']) : ?>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Catatan Admin</td>
                <td style="padding:10px;"><?= htmlspecialchars($pembayaran['catatan_admin']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($pembayaran['tanggal_konfirmasi']) : ?>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Tanggal Konfirmasi</td>
                <td style="padding:10px;"><?= date('d/m/Y H:i', strtotime($pembayaran['tanggal_konfirmasi'])) ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <h4 style="margin-bottom:10px;">Bukti Pembayaran</h4>
        <?php if ($pembayaran['bukti_bayar']) : ?>
            <img src="<?= BASE_URL; ?>/uploads/bukti_bayar/<?= $pembayaran['bukti_bayar'] ?>" width="100%" style="border-radius:6px; border:1px solid #ddd; box-shadow:0 2px 8px rgba(0,0,0,.1);">
        <?php else : ?>
            <div style="padding:30px; background:#f8f9fa; border-radius:6px; text-align:center; border:1px dashed #ddd;">
                <p style="color:#95a5a6;">Tidak ada bukti pembayaran</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div style="margin-top:20px; display:flex; gap:10px;">
    <?php if ($pembayaran['status'] === 'dikonfirmasi') : ?>
        <a class="btn" href="kuitansi.php?id=<?= $pembayaran['id'] ?>" target="_blank" style="background:#8e44ad;">Cetak Kuitansi</a>
    <?php endif; ?>
    <a class="btn" href="<?= $backUrl ?>" style="background:#95a5a6;">Kembali</a>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
