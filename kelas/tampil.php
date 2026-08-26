<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala']);

$judul = 'Data Kelas';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_tingkat = isset($_GET['tingkat']) ? mysqli_real_escape_string($koneksi, $_GET['tingkat']) : '';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (k.namakelas LIKE '%$search%' OR g.nama LIKE '%$search%' OR s.nama LIKE '%$search%' OR r.nama_ruang LIKE '%$search%')";
}
if ($filter_tingkat) {
    $where .= " AND k.tingkat = '$filter_tingkat'";
}

$countQuery = "SELECT COUNT(*) as total FROM datakelas k 
    LEFT JOIN dataguru g ON k.idguru = g.id
    LEFT JOIN datasiswa s ON k.idsiswa = s.id
    LEFT JOIN dataruang r ON k.idruang = r.id_ruang
    $where";
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $countQuery))['total'];
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT k.*, 
                 g.nama AS nama_guru, 
                 s.nama AS nama_siswa, 
                 r.nama_ruang,
                 (SELECT COUNT(*) FROM datasiswa WHERE id_kelas = k.id_kelas) AS anggota
          FROM datakelas k
          LEFT JOIN dataguru g ON k.idguru = g.id
          LEFT JOIN datasiswa s ON k.idsiswa = s.id
          LEFT JOIN dataruang r ON k.idruang = r.id_ruang
          $where
          ORDER BY k.tingkat, k.namakelas LIMIT $perPage OFFSET $offset";
$data = mysqli_query($koneksi, $query);

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Data Kelas</h2>

<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
    <div style="flex: 1; min-width: 200px;">
        <label>Cari Nama Kelas/Wali/Ketua/Ruang:</label><br>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Ketik untuk mencari...">
    </div>
    <div style="min-width: 130px;">
        <label>Filter Tingkat:</label><br>
        <select name="tingkat">
            <option value="">-- Semua --</option>
            <option value="X" <?= $filter_tingkat === 'X' ? 'selected' : '' ?>>X</option>
            <option value="XI" <?= $filter_tingkat === 'XI' ? 'selected' : '' ?>>XI</option>
            <option value="XII" <?= $filter_tingkat === 'XII' ? 'selected' : '' ?>>XII</option>
        </select>
    </div>
    <?php if (hasRole('admin')): ?>
    <div style="display: flex; flex-direction: column;">
        <label style="visibility: hidden;">Aksi</label>
        <div style="display: flex; gap: 5px;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn" href="tampil.php" style="background:#95a5a6;">Reset</a>
            <a class="btn btn-success" href="index.php">Tambah Data</a>
        </div>
    </div>
    <?php else: ?>
    <div style="display: flex; flex-direction: column;">
        <label style="visibility: hidden;">Aksi</label>
        <div style="display: flex; gap: 5px;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn" href="tampil.php" style="background:#95a5a6;">Reset</a>
        </div>
    </div>
    <?php endif; ?>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Nama Kelas</th>
        <th>Tingkat</th>
        <th>Wali Kelas</th>
        <th>Ketua Kelas</th>
        <th>Ruangan</th>
        <th>Anggota</th>
        <th>Aksi</th>
    </tr>

    <?php 
    $no = $offset + 1;
    if (mysqli_num_rows($data) === 0) : ?>
        <tr>
            <td colspan="8" style="text-align:center; padding:20px;">Tidak ada data</td>
        </tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['namakelas']) ?></td>
                <td><?= htmlspecialchars($row['tingkat']) ?></td>
                <td><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_ruang'] ?? '-') ?></td>
                <td style="text-align:center; font-weight:bold;"><?= $row['anggota'] ?></td>
                <td>
                    <a class="btn" href="detail.php?id=<?= $row['id_kelas'] ?>" style="padding:5px 10px; font-size:12px;">Detail</a>
                    <?php if (hasRole('admin')): ?>
                    <a class="btn" href="edit.php?id=<?= $row['id_kelas'] ?>" style="padding:5px 10px; font-size:12px;">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_kelas'] ?>" onclick="return confirm('Yakin ingin menghapus?')" style="padding:5px 10px; font-size:12px;">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<?php if ($totalPages > 1) : ?>
<div style="margin-top: 20px; display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
    <?php if ($page > 1) : ?>
        <a class="btn" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&tingkat=<?= urlencode($filter_tingkat) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++) : ?>
        <a class="btn" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&tingkat=<?= urlencode($filter_tingkat) ?>" style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    <?php if ($page < $totalPages) : ?>
        <a class="btn" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&tingkat=<?= urlencode($filter_tingkat) ?>">Next »</a>
    <?php endif; ?>
</div>
<p style="text-align:center; margin-top:10px; color:#7f8c8d;">Total: <?= $total ?> data | Halaman <?= $page ?> dari <?= $totalPages ?></p>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>