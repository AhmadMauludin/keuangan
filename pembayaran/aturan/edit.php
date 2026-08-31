<?php
include dirname(__DIR__, 2) . '/koneksi.php';
include dirname(__DIR__, 2) . '/auth.php';
requireRole('admin');

$judul = 'Edit Aturan Pembayaran';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_query($koneksi, "SELECT * FROM aturan_pembayaran WHERE id=$id");
$aturan = mysqli_fetch_assoc($data);

if (!$aturan) {
    header("location:tampil.php");
    exit;
}

$kelasList = mysqli_query($koneksi, "SELECT id_kelas, namakelas FROM datakelas ORDER BY namakelas");
$siswaList = mysqli_query($koneksi, "SELECT id, nama FROM datasiswa ORDER BY nama");

// Parse existing target_id into array
$checked_kelas = [];
$checked_tingkat = [];
$checked_siswa = [];
if ($aturan['target_id'] && $aturan['target_id'] !== '') {
    $ids = explode(',', $aturan['target_id']);
    if ($aturan['target'] === 'kelas') $checked_kelas = $ids;
    elseif ($aturan['target'] === 'tingkat') $checked_tingkat = $ids;
    elseif ($aturan['target'] === 'siswa') $checked_siswa = $ids;
}

include dirname(__DIR__, 2) . '/layout/header.php';
?>

<h2>Edit Aturan Pembayaran</h2>

<form action="update.php" method="post">
    <input type="hidden" name="id" value="<?= $aturan['id']; ?>">

    <label>Nama Biaya:</label><br>
    <input type="text" name="nama_biaya" value="<?= htmlspecialchars($aturan['nama_biaya']); ?>" required><br>

    <label>Nominal (Rp):</label><br>
    <input type="number" name="nominal" value="<?= $aturan['nominal']; ?>" min="0" required><br>

    <label>Berlaku Untuk:</label><br>
    <select name="target" id="target" required onchange="toggleTarget()">
        <option value="semua" <?= $aturan['target'] === 'semua' ? 'selected' : '' ?>>Semua Siswa</option>
        <option value="kelas" <?= $aturan['target'] === 'kelas' ? 'selected' : '' ?>>Berdasarkan Kelas</option>
        <option value="tingkat" <?= $aturan['target'] === 'tingkat' ? 'selected' : '' ?>>Berdasarkan Tingkat</option>
        <option value="siswa" <?= $aturan['target'] === 'siswa' ? 'selected' : '' ?>>Berdasarkan Siswa</option>
    </select><br>

    <div id="target_kelas_wrap" style="display:<?= $aturan['target'] === 'kelas' ? 'block' : 'none' ?>;">
        <label>Pilih Kelas (bisa lebih dari satu):</label><br>
        <div style="border:1px solid #ccc; border-radius:4px; padding:10px; max-height:200px; overflow-y:auto; max-width:400px; background:#fff;">
            <?php while ($k = mysqli_fetch_assoc($kelasList)) : ?>
                <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                    <input type="checkbox" name="target_id_kelas[]" value="<?= $k['id_kelas'] ?>" <?= in_array($k['id_kelas'], $checked_kelas) ? 'checked' : '' ?>> <?= htmlspecialchars($k['namakelas']) ?>
                </label>
            <?php endwhile; ?>
        </div><br>
    </div>

    <div id="target_tingkat_wrap" style="display:<?= $aturan['target'] === 'tingkat' ? 'block' : 'none' ?>;">
        <label>Pilih Tingkat (bisa lebih dari satu):</label><br>
        <div style="border:1px solid #ccc; border-radius:4px; padding:10px; max-width:400px; background:#fff;">
            <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                <input type="checkbox" name="target_id_tingkat[]" value="10" <?= in_array('10', $checked_tingkat) ? 'checked' : '' ?>> Tingkat X
            </label>
            <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                <input type="checkbox" name="target_id_tingkat[]" value="11" <?= in_array('11', $checked_tingkat) ? 'checked' : '' ?>> Tingkat XI
            </label>
            <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                <input type="checkbox" name="target_id_tingkat[]" value="12" <?= in_array('12', $checked_tingkat) ? 'checked' : '' ?>> Tingkat XII
            </label>
        </div><br>
    </div>

    <div id="target_siswa_wrap" style="display:<?= $aturan['target'] === 'siswa' ? 'block' : 'none' ?>;">
        <label>Pilih Siswa (bisa lebih dari satu):</label><br>
        <div style="border:1px solid #ccc; border-radius:4px; padding:10px; max-height:200px; overflow-y:auto; max-width:400px; background:#fff;">
            <?php while ($s = mysqli_fetch_assoc($siswaList)) : ?>
                <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                    <input type="checkbox" name="target_id_siswa[]" value="<?= $s['id'] ?>" <?= in_array($s['id'], $checked_siswa) ? 'checked' : '' ?>> <?= htmlspecialchars($s['nama']) ?>
                </label>
            <?php endwhile; ?>
        </div><br>
    </div>

    <label>Keterangan:</label><br>
    <input type="text" name="keterangan" value="<?= htmlspecialchars($aturan['keterangan'] ?? ''); ?>"><br>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<script>
function toggleTarget() {
    var target = document.getElementById('target').value;
    document.getElementById('target_kelas_wrap').style.display = (target === 'kelas') ? 'block' : 'none';
    document.getElementById('target_tingkat_wrap').style.display = (target === 'tingkat') ? 'block' : 'none';
    document.getElementById('target_siswa_wrap').style.display = (target === 'siswa') ? 'block' : 'none';
}
</script>

<?php include dirname(__DIR__, 2) . '/layout/footer.php'; ?>
