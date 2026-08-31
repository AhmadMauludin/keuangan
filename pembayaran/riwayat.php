<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireLogin();

$judul = 'Riwayat Pembayaran';
$user = getCurrentUser();

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_status = isset($_GET['status_filter']) ? mysqli_real_escape_string($koneksi, $_GET['status_filter']) : '';
$filter_metode = isset($_GET['metode_filter']) ? mysqli_real_escape_string($koneksi, $_GET['metode_filter']) : '';
$tgl_dari = isset($_GET['tgl_dari']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_dari']) : '';
$tgl_sampai = isset($_GET['tgl_sampai']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_sampai']) : '';

$where = "WHERE 1=1";
if ($user['role'] === 'user' && $user['id_siswa']) {
    $where .= " AND p.id_siswa = {$user['id_siswa']}";
}
if ($search) {
    $where .= " AND (s.nama LIKE '%$search%' OR a.nama_biaya LIKE '%$search%')";
}
if ($filter_status) {
    $where .= " AND p.status = '$filter_status'";
}
if ($filter_metode) {
    $where .= " AND p.metode_bayar = '$filter_metode'";
}
if ($tgl_dari) {
    $where .= " AND p.tanggal_bayar >= '$tgl_dari'";
}
if ($tgl_sampai) {
    $where .= " AND p.tanggal_bayar <= '$tgl_sampai'";
}

$countQuery = "SELECT COUNT(*) as total FROM pembayaran p
               LEFT JOIN datasiswa s ON p.id_siswa = s.id
               LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
               $where";
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $countQuery))['total'];
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT p.*, s.nama as nama_siswa, k.namakelas, a.nama_biaya, a.nominal as nominal_aturan
          FROM pembayaran p
          LEFT JOIN datasiswa s ON p.id_siswa = s.id
          LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
          $where
          ORDER BY p.tanggal_bayar DESC, p.created_at DESC
          LIMIT $perPage OFFSET $offset";
$data = mysqli_query($koneksi, $query);

$filterParams = http_build_query(array_filter([
    'search' => $search, 'status_filter' => $filter_status,
    'metode_filter' => $filter_metode, 'tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai
]));

$statusColor = [
    'belum' => '#95a5a6', 'pending' => '#f39c12',
    'dikonfirmasi' => '#27ae60', 'ditolak' => '#e74c3c'
];
$metodeLabel = ['cash' => 'Cash', 'transfer' => 'Transfer', 'ewallet' => 'E-Wallet'];

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Riwayat Pembayaran</h2>

<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
    <div style="flex: 1; min-width: 150px;">
        <label>Cari:</label><br>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nama siswa / biaya...">
    </div>
    <div style="min-width: 120px;">
        <label>Status:</label><br>
        <select name="status_filter">
            <option value="">-- Semua --</option>
            <option value="belum" <?= $filter_status === 'belum' ? 'selected' : '' ?>>Belum Dibayar</option>
            <option value="pending" <?= $filter_status === 'pending' ? 'selected' : '' ?>>Menunggu</option>
            <option value="dikonfirmasi" <?= $filter_status === 'dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
            <option value="ditolak" <?= $filter_status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
        </select>
    </div>
    <div style="min-width: 120px;">
        <label>Metode:</label><br>
        <select name="metode_filter">
            <option value="">-- Semua --</option>
            <option value="cash" <?= $filter_metode === 'cash' ? 'selected' : '' ?>>Cash</option>
            <option value="transfer" <?= $filter_metode === 'transfer' ? 'selected' : '' ?>>Transfer</option>
            <option value="ewallet" <?= $filter_metode === 'ewallet' ? 'selected' : '' ?>>E-Wallet</option>
        </select>
    </div>
    <div style="min-width: 130px;">
        <label>Dari Tanggal Bayar:</label><br>
        <input type="date" name="tgl_dari" value="<?= htmlspecialchars($tgl_dari) ?>">
    </div>
    <div style="min-width: 130px;">
        <label>Sampai Tanggal Bayar:</label><br>
        <input type="date" name="tgl_sampai" value="<?= htmlspecialchars($tgl_sampai) ?>">
    </div>
    <div style="display: flex; gap: 5px; align-items: end;">
        <button class="btn" type="submit">Filter</button>
        <a class="btn" href="riwayat.php" style="background:#95a5a6;">Reset</a>
        <?php if (hasRole(['admin', 'kepala'])) : ?>
            <a class="btn" href="cetak_riwayat.php?<?= $filterParams ?>" target="_blank" style="background:#8e44ad;">Cetak</a>
        <?php endif; ?>
    </div>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Tgl Bayar</th>
        <th>Siswa</th>
        <th>Kelas</th>
        <th>Jenis Biaya</th>
        <th>Nominal</th>
        <th>Metode</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = $offset + 1;
    if (mysqli_num_rows($data) === 0) : ?>
        <tr><td colspan="9" style="text-align:center; padding:20px;">Tidak ada data</td></tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal_bayar'])) ?></td>
                <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['namakelas'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_biaya'] ?? '-') ?></td>
                <td style="font-weight:bold;">Rp <?= number_format($row['nominal_bayar'], 0, ',', '.') ?></td>
                <td><span style="padding:3px 8px; border-radius:3px; background:#3498db; color:#fff; font-size:12px;"><?= $metodeLabel[$row['metode_bayar']] ?? $row['metode_bayar'] ?></span></td>
                <td><span style="padding:3px 8px; border-radius:3px; background:<?= $statusColor[$row['status']] ?>; color:#fff; font-size:12px;"><?= ucfirst(str_replace('_', ' ', $row['status'])) ?></span></td>
                <td style="white-space:nowrap;">
                    <a class="btn" href="detail_pembayaran.php?id=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Detail</a>
                    <?php if ($row['status'] === 'dikonfirmasi') : ?>
                        <a class="btn" href="kuitansi.php?id=<?= $row['id'] ?>" target="_blank" style="padding:5px 10px; font-size:12px; background:#8e44ad;">Kuitansi</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<?php if ($totalPages > 1) : ?>
<div style="margin-top: 20px; display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
    <?php if ($page > 1) : ?>
        <a class="btn" href="?page=<?= $page - 1 ?>&<?= $filterParams ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) : ?>
        <a class="btn" href="?page=<?= $i ?>&<?= $filterParams ?>" style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages) : ?>
        <a class="btn" href="?page=<?= $page + 1 ?>&<?= $filterParams ?>">Next »</a>
    <?php endif; ?>
</div>
<p style="text-align:center; margin-top:10px; color:#7f8c8d;">Total: <?= $total ?> data | Halaman <?= $page ?> dari <?= $totalPages ?></p>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
