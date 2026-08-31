<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireLogin();

$judul = 'Tagihan & Pembayaran';
$user = getCurrentUser();

$siswa = null;
if ($user['role'] === 'user' && $user['id_siswa']) {
    $siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT s.*, k.namakelas, k.tingkat FROM datasiswa s LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas WHERE s.id={$user['id_siswa']}"));
}

$daftar_bayar = [];
if ($siswa) {
    $query = "SELECT p.*, a.nama_biaya, a.nominal AS nominal_aturan, a.keterangan AS keterangan_biaya
              FROM pembayaran p
              LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
              WHERE p.id_siswa = {$siswa['id']}
              ORDER BY FIELD(p.status, 'pending', 'belum', 'ditolak', 'dikonfirmasi'), a.nama_biaya ASC";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $daftar_bayar[] = $row;
    }
}

$statusColor = [
    'belum' => '#95a5a6',
    'pending' => '#f39c12',
    'dikonfirmasi' => '#27ae60',
    'ditolak' => '#e74c3c'
];
$statusLabel = [
    'belum' => 'Belum Dibayar',
    'pending' => 'Menunggu Konfirmasi',
    'dikonfirmasi' => 'Dikonfirmasi',
    'ditolak' => 'Ditolak'
];
$metodeLabel = [
    '' => '-',
    'cash' => 'Cash',
    'transfer' => 'Transfer',
    'ewallet' => 'E-Wallet'
];

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tagihan & Pembayaran</h2>

<?php if (!$siswa) : ?>
    <div style="padding:20px; background:#fadbd8; border-radius:6px; border-left:4px solid #e74c3c;">
        <p style="color:#c0392b; font-weight:bold;">Akun Anda tidak terkait dengan data siswa. Silakan hubungi administrator.</p>
    </div>
<?php else : ?>
    <div style="background:#d5f5e3; padding:15px 20px; border-radius:6px; margin-bottom:20px; border-left:4px solid #27ae60;">
        <p style="margin:0;"><strong>Siswa:</strong> <?= htmlspecialchars($siswa['nama']) ?></p>
        <p style="margin:3px 0 0;"><strong>Kelas:</strong> <?= htmlspecialchars($siswa['namakelas'] ?? '-') ?> | <strong>Tingkat:</strong> <?= htmlspecialchars($siswa['tingkat'] ?? '-') ?></p>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses') : ?>
        <div style="padding:12px 20px; background:#d5f5e3; border-radius:6px; border-left:4px solid #27ae60; margin-bottom:20px;">
            <p style="margin:0; color:#27ae60; font-weight:bold;">Pembayaran berhasil dikirim! Menunggu konfirmasi admin.</p>
        </div>
    <?php endif; ?>

    <?php
    $belum_bayar = 0;
    $pending = 0;
    $dikonfirmasi = 0;
    $ditolak = 0;
    foreach ($daftar_bayar as $d) {
        if ($d['status'] === 'belum') $belum_bayar++;
        elseif ($d['status'] === 'pending') $pending++;
        elseif ($d['status'] === 'dikonfirmasi') $dikonfirmasi++;
        elseif ($d['status'] === 'ditolak') $ditolak++;
    }
    ?>

    <div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:20px;">
        <div style="flex:1; min-width:100px; background:#3498db; color:#fff; padding:12px; border-radius:6px; text-align:center;">
            <h3 style="margin:0;"><?= count($daftar_bayar) ?></h3>
            <p style="margin:3px 0 0; font-size:13px;">Total</p>
        </div>
        <div style="flex:1; min-width:100px; background:#e74c3c; color:#fff; padding:12px; border-radius:6px; text-align:center;">
            <h3 style="margin:0;"><?= $belum_bayar ?></h3>
            <p style="margin:3px 0 0; font-size:13px;">Belum Bayar</p>
        </div>
        <div style="flex:1; min-width:100px; background:#f39c12; color:#fff; padding:12px; border-radius:6px; text-align:center;">
            <h3 style="margin:0;"><?= $pending ?></h3>
            <p style="margin:3px 0 0; font-size:13px;">Menunggu</p>
        </div>
        <div style="flex:1; min-width:100px; background:#27ae60; color:#fff; padding:12px; border-radius:6px; text-align:center;">
            <h3 style="margin:0;"><?= $dikonfirmasi ?></h3>
            <p style="margin:3px 0 0; font-size:13px;">Lunas</p>
        </div>
    </div>

    <?php if (count($daftar_bayar) === 0) : ?>
        <div style="padding:20px; background:#fef9e7; border-radius:6px; border-left:4px solid #f39c12;">
            <p style="color:#b7950b; font-weight:bold;">Belum ada tagihan pembayaran untuk Anda.</p>
        </div>
    <?php else : ?>
    <table>
        <tr>
            <th>No</th>
            <th>Jenis Biaya</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; foreach ($daftar_bayar as $row) : ?>
            <tr style="<?= $row['status'] === 'belum' ? 'background:#fdf2f2;' : ($row['status'] === 'pending' ? 'background:#fffde7;' : '') ?>">
                <td><?= $no++ ?></td>
                <td>
                    <strong><?= htmlspecialchars($row['nama_biaya'] ?? '-') ?></strong>
                    <?php if ($row['keterangan_biaya']) : ?>
                        <br><small style="color:#7f8c8d;"><?= htmlspecialchars($row['keterangan_biaya']) ?></small>
                    <?php endif; ?>
                </td>
                <td style="font-weight:bold;">Rp <?= number_format($row['nominal_aturan'], 0, ',', '.') ?></td>
                <td><?= $metodeLabel[$row['metode_bayar']] ?? '-' ?></td>
                <td>
                    <span style="padding:3px 8px; border-radius:3px; background:<?= $statusColor[$row['status']] ?>; color:#fff; font-size:12px;">
                        <?= $statusLabel[$row['status']] ?>
                    </span>
                </td>
                <td style="white-space:nowrap;">
                    <a class="btn" href="detail_pembayaran.php?id=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Detail</a>
                    <?php if ($row['status'] === 'belum' || $row['status'] === 'ditolak') : ?>
                        <a class="btn btn-success" href="bayar.php?id_bayar=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Bayar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php
    $id_bayar = isset($_GET['id_bayar']) ? (int)$_GET['id_bayar'] : 0;
    if ($id_bayar) :
        $bayar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT p.*, a.nama_biaya, a.nominal AS nominal_aturan
                  FROM pembayaran p LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
                  WHERE p.id=$id_bayar AND p.id_siswa={$siswa['id']} AND p.status IN ('belum','ditolak')"));
        if ($bayar) :
    ?>
    <div style="margin-top:25px; background:#fff; padding:20px; border-radius:6px; border:2px solid #27ae60;">
        <h3 style="margin-top:0; color:#27ae60;">Form Pembayaran</h3>
        <p><strong>Jenis Biaya:</strong> <?= htmlspecialchars($bayar['nama_biaya']) ?></p>
        <p><strong>Nominal:</strong> Rp <?= number_format($bayar['nominal_aturan'], 0, ',', '.') ?></p>

        <form action="proses_bayar.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_bayar" value="<?= $bayar['id'] ?>">

            <label>Metode Pembayaran:</label><br>
            <select name="metode_bayar" id="metode_bayar" required onchange="toggleBukti()">
                <option value="">-- Pilih Metode --</option>
                <option value="cash">Cash (Tunai)</option>
                <option value="transfer">Transfer Bank</option>
                <option value="ewallet">E-Wallet</option>
            </select><br>

            <div id="info_metode" style="display:none; padding:12px; border-radius:4px; margin-bottom:15px;">
                <p id="info_metode_text"></p>
            </div>

            <div id="bukti_section" style="display:none;">
                <label>Bukti Pembayaran:</label><br>
                <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/*">
                <p style="font-size:12px; color:#7f8c8d; margin-top:5px;">Format: JPG, PNG, JPEG. Maks 5MB.</p><br>
            </div>

            <label>Keterangan (opsional):</label><br>
            <input type="text" name="keterangan" placeholder="Contoh: Pembayaran SPP bulan Januari"><br>

            <div style="display:flex; gap:10px; margin-top:10px;">
                <button class="btn btn-success" type="submit">Kirim Pembayaran</button>
                <a class="btn" href="bayar.php" style="background:#95a5a6;">Batal</a>
            </div>
        </form>
    </div>

    <script>
    function toggleBukti() {
        var metode = document.getElementById('metode_bayar').value;
        var buktiSection = document.getElementById('bukti_section');
        var infoSection = document.getElementById('info_metode');
        var infoText = document.getElementById('info_metode_text');
        var buktiInput = document.getElementById('bukti_bayar');

        if (metode === 'transfer') {
            buktiSection.style.display = 'block'; buktiInput.required = true;
            infoSection.style.display = 'block'; infoSection.style.background = '#ebf5fb';
            infoText.innerHTML = '<strong>Transfer Bank:</strong> Transfer ke BCA: 1234567890 a/n SMA Nusantara. Upload bukti transfer.';
        } else if (metode === 'ewallet') {
            buktiSection.style.display = 'block'; buktiInput.required = true;
            infoSection.style.display = 'block'; infoSection.style.background = '#f4ecf7';
            infoText.innerHTML = '<strong>E-Wallet:</strong> Transfer ke GoPay/OVO/DANA: 081234567890 a/n SMA Nusantara. Upload bukti transfer.';
        } else if (metode === 'cash') {
            buktiSection.style.display = 'block'; buktiInput.required = false;
            infoSection.style.display = 'block'; infoSection.style.background = '#eafaf1';
            infoText.innerHTML = '<strong>Cash/Tunai:</strong> Bayar langsung ke bendahara sekolah.';
        } else {
            buktiSection.style.display = 'none'; buktiInput.required = false;
            infoSection.style.display = 'none';
        }
    }
    </script>
    <?php
        endif;
    endif;
    ?>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
