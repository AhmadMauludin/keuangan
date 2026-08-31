<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Bayar Langsung';

$siswaList = mysqli_query($koneksi, "SELECT s.id, s.nama, k.namakelas FROM datasiswa s LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas WHERE s.status='aktif' ORDER BY s.nama");
$aturanList = mysqli_query($koneksi, "SELECT id, nama_biaya, nominal, target, target_id FROM aturan_pembayaran ORDER BY nama_biaya");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Bayar Langsung (Admin)</h2>
<p style="color:#7f8c8d; margin-bottom:20px;">Gunakan halaman ini untuk mencatat pembayaran yang diterima langsung (cash/tunai) dari siswa. Pembayaran akan langsung terkonfirmasi.</p>

<?php if (isset($_GET['error'])) : ?>
    <div style="padding:12px 20px; background:#fadbd8; border-radius:6px; border-left:4px solid #e74c3c; margin-bottom:20px;">
        <p style="margin:0; color:#c0392b; font-weight:bold;">Terjadi kesalahan. Silakan coba lagi.</p>
    </div>
<?php endif; ?>

<form action="proses_bayar_langsung.php" method="post" style="background:#fff; padding:20px; border-radius:6px; border:1px solid #ddd;">
    <label>Nama Siswa:</label><br>
    <select name="id_siswa" id="id_siswa" required onchange="loadAturan()">
        <option value="">-- Pilih Siswa --</option>
        <?php while ($s = mysqli_fetch_assoc($siswaList)) : ?>
            <option value="<?= $s['id'] ?>" data-kelas="<?= htmlspecialchars($s['namakelas']) ?>"><?= htmlspecialchars($s['nama']) ?> (<?= htmlspecialchars($s['namakelas']) ?>)</option>
        <?php endwhile; ?>
    </select><br>

    <label>Jenis Biaya:</label><br>
    <select name="id_aturan" id="id_aturan" required onchange="updateNominal()">
        <option value="">-- Pilih Jenis Biaya --</option>
        <?php while ($a = mysqli_fetch_assoc($aturanList)) : ?>
            <option value="<?= $a['id'] ?>" data-nominal="<?= $a['nominal'] ?>" data-target="<?= $a['target'] ?>" data-target-id="<?= $a['target_id'] ?>">
                <?= htmlspecialchars($a['nama_biaya']) ?> - Rp <?= number_format($a['nominal'], 0, ',', '.') ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <label>Nominal Bayar (Rp):</label><br>
    <input type="number" name="nominal_bayar" id="nominal_bayar" min="0" required><br>

    <label>Metode Pembayaran:</label><br>
    <select name="metode_bayar" required>
        <option value="cash">Cash (Tunai)</option>
        <option value="transfer">Transfer Bank</option>
        <option value="ewallet">E-Wallet</option>
    </select><br>

    <label>Tanggal Bayar:</label><br>
    <input type="date" name="tanggal_bayar" value="<?= date('Y-m-d') ?>" required><br>

    <label>Keterangan (opsional):</label><br>
    <input type="text" name="keterangan" placeholder="Contoh: Pembayaran langsung di sekolah"><br>

    <div style="display:flex; gap:10px; margin-top:15px;">
        <button class="btn btn-success" type="submit" onclick="return confirm('Konfirmasi pembayaran ini?')">Simpan & Konfirmasi</button>
        <a class="btn" href="konfirmasi.php" style="background:#95a5a6;">Batal</a>
    </div>
</form>

<script>
function updateNominal() {
    var select = document.getElementById('id_aturan');
    var nominalInput = document.getElementById('nominal_bayar');
    var selected = select.options[select.selectedIndex];
    if (selected && selected.value) {
        nominalInput.value = selected.getAttribute('data-nominal');
    } else {
        nominalInput.value = '';
    }
}
</script>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
