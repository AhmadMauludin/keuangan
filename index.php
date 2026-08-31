<?php
include __DIR__ . '/koneksi.php';
include __DIR__ . '/auth.php';
requireLogin();

$judul = 'Dashboard';

$jml_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM datasiswa"))['total'];
$jml_guru  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dataguru"))['total'];
$jml_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM datakelas"))['total'];
$jml_ruang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dataruang"))['total'];

$bayar_pending    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pembayaran WHERE status='pending'"))['total'];
$bayar_dikonfirmasi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pembayaran WHERE status='dikonfirmasi'"))['total'];
$total_pemasukan  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(nominal_bayar),0) AS total FROM pembayaran WHERE status='dikonfirmasi'"))['total'];

include BASE_PATH . '/layout/header.php';
?>

<h2>Dashboard</h2>

<p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong>! Anda login sebagai <span style="text-transform: capitalize;"><?= $_SESSION['role'] ?></span>.</p>
<br>

<?php if (hasRole('user')) : ?>
<div style="margin-bottom:20px;">
    <a class="btn btn-success" href="<?= BASE_URL; ?>/pembayaran/bayar.php" style="font-size:16px; padding:12px 24px;">Lihat Tagihan</a>
</div>
<?php endif; ?>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 200px; background: #3498db; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_siswa; ?></h3>
        <p>Data Siswa</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/siswa/tampil.php" style="background:#fff; color:#3498db;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #e67e22; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_guru; ?></h3>
        <p>Data Guru</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/guru/tampil.php" style="background:#fff; color:#e67e22;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #9b59b6; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_kelas; ?></h3>
        <p>Data Kelas</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/kelas/tampil.php" style="background:#fff; color:#9b59b6;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #1abc9c; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_ruang; ?></h3>
        <p>Data Ruangan</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/ruang/tampil.php" style="background:#fff; color:#1abc9c;">Lihat</a>
    </div>
</div>

<?php if (hasRole(['admin', 'kepala'])) : ?>
<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
    <div style="flex: 1; min-width: 200px; background: #f39c12; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $bayar_pending; ?></h3>
        <p>Pembayaran Pending</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/pembayaran/konfirmasi.php" style="background:#fff; color:#f39c12;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #27ae60; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $bayar_dikonfirmasi; ?></h3>
        <p>Pembayaran Dikonfirmasi</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/pembayaran/riwayat.php" style="background:#fff; color:#27ae60;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #2980b9; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></h3>
        <p>Total Pemasukan</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/pembayaran/riwayat.php" style="background:#fff; color:#2980b9;">Lihat</a>
    </div>
</div>
<?php endif; ?>

<?php include BASE_PATH . '/layout/footer.php'; ?>