<?php
include dirname(__DIR__, 2) . '/koneksi.php';
include dirname(__DIR__, 2) . '/auth.php';
requireRole('admin');

$judul = 'Aturan Pembayaran';

function getTargetDisplay($target, $target_id) {
    if ($target === 'semua') return 'Semua Siswa';
    if (!$target_id) return '-';
    $ids = explode(',', $target_id);
    $labels = [];
    $conn = $GLOBALS['koneksi'];
    foreach ($ids as $id) {
        $id = trim($id);
        if ($id === '') continue;
        if ($target === 'kelas') {
            $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT namakelas FROM datakelas WHERE id_kelas=$id"));
            $labels[] = $r['namakelas'] ?? "Kelas $id";
        } elseif ($target === 'tingkat') {
            $map = [10 => 'X', 11 => 'XI', 12 => 'XII'];
            $labels[] = 'Tingkat ' . ($map[$id] ?? $id);
        } elseif ($target === 'siswa') {
            $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM datasiswa WHERE id=$id"));
            $labels[] = $r['nama'] ?? "Siswa $id";
        }
    }
    return implode(', ', $labels) ?: '-';
}

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (a.nama_biaya LIKE '%$search%' OR a.keterangan LIKE '%$search%')";
}

$countQuery = "SELECT COUNT(*) as total FROM aturan_pembayaran a $where";
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $countQuery))['total'];
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT a.* FROM aturan_pembayaran a $where ORDER BY a.id DESC LIMIT $perPage OFFSET $offset";
$data = mysqli_query($koneksi, $query);

$targetColors = ['semua' => '#3498db', 'kelas' => '#9b59b6', 'tingkat' => '#e67e22', 'siswa' => '#27ae60'];
$targetLabels = ['semua' => 'Semua', 'kelas' => 'Kelas', 'tingkat' => 'Tingkat', 'siswa' => 'Siswa'];

include dirname(__DIR__, 2) . '/layout/header.php';
?>

<h2>Aturan Pembayaran</h2>

<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
    <div style="flex: 1; min-width: 150px;">
        <label>Cari Nama Biaya:</label><br>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Ketik untuk mencari...">
    </div>
    <div style="display: flex; gap: 5px; align-items: end;">
        <button class="btn" type="submit">Filter</button>
        <a class="btn" href="tampil.php" style="background:#95a5a6;">Reset</a>
        <a class="btn btn-success" href="index.php">Tambah Aturan</a>
    </div>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Nama Biaya</th>
        <th>Nominal</th>
        <th>Berlaku Untuk</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = $offset + 1;
    if (mysqli_num_rows($data) === 0) : ?>
        <tr>
            <td colspan="6" style="text-align:center; padding:20px;">Tidak ada data</td>
        </tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_biaya']) ?></td>
                <td style="font-weight:bold;">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                <td>
                    <span style="padding:3px 8px; border-radius:3px; background:<?= $targetColors[$row['target']] ?? '#95a5a6' ?>; color:#fff; font-size:12px;">
                        <?= $targetLabels[$row['target']] ?>
                    </span>
                    <span style="font-size:12px; color:#555; margin-left:5px;">
                        <?= htmlspecialchars(getTargetDisplay($row['target'], $row['target_id'])) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                <td>
                    <a class="btn" href="edit.php?id=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" style="padding:5px 10px; font-size:12px;">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<?php if ($totalPages > 1) : ?>
<div style="margin-top: 20px; display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
    <?php if ($page > 1) : ?>
        <a class="btn" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) : ?>
        <a class="btn" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    <?php if ($page < $totalPages) : ?>
        <a class="btn" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next »</a>
    <?php endif; ?>
</div>
<p style="text-align:center; margin-top:10px; color:#7f8c8d;">Total: <?= $total ?> data | Halaman <?= $page ?> dari <?= $totalPages ?></p>
<?php endif; ?>

<?php include dirname(__DIR__, 2) . '/layout/footer.php'; ?>
