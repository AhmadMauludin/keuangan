<?php
include dirname(__DIR__, 2) . '/koneksi.php';
include dirname(__DIR__, 2) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Aturan Pembayaran';

$kelasList = mysqli_query($koneksi, "SELECT id_kelas, namakelas FROM datakelas ORDER BY namakelas");
$siswaList = mysqli_query($koneksi, "SELECT id, nama FROM datasiswa ORDER BY nama");

include dirname(__DIR__, 2) . '/layout/header.php';
?>

<h2>Tambah Aturan Pembayaran</h2>

<form action="simpan.php" method="post">
    <label>Nama Biaya:</label><br>
    <input type="text" name="nama_biaya" placeholder="Contoh: SPP Bulanan" required><br>

    <label>Nominal (Rp):</label><br>
    <input type="number" name="nominal" placeholder="Contoh: 350000" min="0" required><br>

    <label>Berlaku Untuk:</label><br>
    <select name="target" id="target" required onchange="toggleTarget()">
        <option value="semua">Semua Siswa</option>
        <option value="kelas">Berdasarkan Kelas</option>
        <option value="tingkat">Berdasarkan Tingkat</option>
        <option value="siswa">Berdasarkan Siswa</option>
    </select><br>

    <div id="target_kelas_wrap" style="display:none;">
        <label>Pilih Kelas (bisa lebih dari satu):</label><br>
        <div style="border:1px solid #ccc; border-radius:4px; padding:10px; max-height:200px; overflow-y:auto; max-width:400px; background:#fff;">
            <?php while ($k = mysqli_fetch_assoc($kelasList)) : ?>
                <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                    <input type="checkbox" name="target_id_kelas[]" value="<?= $k['id_kelas'] ?>"> <?= htmlspecialchars($k['namakelas']) ?>
                </label>
            <?php endwhile; ?>
        </div><br>
    </div>

    <div id="target_tingkat_wrap" style="display:none;">
        <label>Pilih Tingkat (bisa lebih dari satu):</label><br>
        <div style="border:1px solid #ccc; border-radius:4px; padding:10px; max-width:400px; background:#fff;">
            <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                <input type="checkbox" name="target_id_tingkat[]" value="10"> Tingkat X
            </label>
            <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                <input type="checkbox" name="target_id_tingkat[]" value="11"> Tingkat XI
            </label>
            <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                <input type="checkbox" name="target_id_tingkat[]" value="12"> Tingkat XII
            </label>
        </div><br>
    </div>

    <div id="target_siswa_wrap" style="display:none;">
        <label>Pilih Siswa (bisa lebih dari satu):</label><br>
        <div style="border:1px solid #ccc; border-radius:4px; padding:10px; max-height:200px; overflow-y:auto; max-width:400px; background:#fff;">
            <?php while ($s = mysqli_fetch_assoc($siswaList)) : ?>
                <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;">
                    <input type="checkbox" name="target_id_siswa[]" value="<?= $s['id'] ?>"> <?= htmlspecialchars($s['nama']) ?>
                </label>
            <?php endwhile; ?>
        </div><br>
    </div>

    <label>Keterangan:</label><br>
    <input type="text" name="keterangan" placeholder="Deskripsi biaya (opsional)"><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php" style="background:#95a5a6;">Lihat Data</a>
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
