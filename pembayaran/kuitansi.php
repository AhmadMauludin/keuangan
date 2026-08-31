<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT p.*, s.nama as nama_siswa, s.alamat as alamat_siswa, k.namakelas, k.tingkat, k.tahunajaran,
          a.nama_biaya, a.nominal as nominal_aturan, a.keterangan as keterangan_biaya
          FROM pembayaran p
          LEFT JOIN datasiswa s ON p.id_siswa = s.id
          LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN aturan_pembayaran a ON p.id_aturan = a.id
          WHERE p.id = $id";
$data = mysqli_query($koneksi, $query);
$pembayaran = mysqli_fetch_assoc($data);

if (!$pembayaran) {
    header("location:" . BASE_URL . "/pembayaran/konfirmasi.php");
    exit;
}

$metodeLabel = [
    'cash' => 'Cash (Tunai)',
    'transfer' => 'Transfer Bank',
    'ewallet' => 'E-Wallet'
];

function terbilang($angka) {
    $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
    if ($angka < 12) return $huruf[$angka];
    if ($angka < 20) return $huruf[$angka - 10] . ' Belas';
    if ($angka < 100) return $huruf[intdiv($angka, 10)] . ' Puluh ' . ($angka % 10 != 0 ? $huruf[$angka % 10] : '');
    if ($angka < 200) return 'Seratus ' . terbilang($angka - 100);
    if ($angka < 1000) return $huruf[intdiv($angka, 100)] . ' Ratus ' . terbilang($angka % 100);
    if ($angka < 2000) return 'Seribu ' . terbilang($angka - 1000);
    if ($angka < 1000000) return terbilang(intdiv($angka, 1000)) . ' Ribu ' . terbilang($angka % 1000);
    return number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi Pembayaran - #<?= str_pad($pembayaran['id'], 6, '0', STR_PAD_LEFT) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Times New Roman', serif; font-size: 13px; color: #000; background: #fff; }
        .kuitansi { max-width: 750px; margin: 0 auto; padding: 30px 40px; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { font-size: 18px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 3px; }
        .header p { font-size: 12px; color: #333; }
        .judul { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0; text-decoration: underline; }
        .info-baris { display: flex; margin-bottom: 6px; }
        .info-label { width: 180px; font-weight: bold; }
        .info-value { flex: 1; }
        .info-label2 { width: 140px; font-weight: bold; }
        .info-value2 { flex: 1; }
        table.detail { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table.detail td, table.detail th { border: 1px solid #000; padding: 8px 12px; font-size: 13px; }
        table.detail th { background: #f0f0f0; text-align: center; }
        table.detail td.nominal { text-align: right; font-weight: bold; }
        .terbilang { background: #f9f9f9; padding: 10px; border: 1px solid #ccc; margin: 15px 0; font-style: italic; }
        .tanda-tangan { display: flex; justify-content: space-between; margin-top: 50px; padding-top: 20px; }
        .tt { text-align: center; width: 200px; }
        .tt .garis { border-top: 1px solid #000; width: 150px; margin: 80px auto 5px; }
        .footer { text-align: center; font-size: 11px; color: #666; margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; }
        .btn-print { display: block; text-align: center; margin: 20px auto; padding: 10px 30px; background: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; text-decoration: none; width: fit-content; }
        .btn-print:hover { background: #2980b9; }
        @media print {
            body { background: #fff; }
            .btn-print { display: none !important; }
            .kuitansi { padding: 0; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="kuitansi">
        <a href="javascript:window.print()" class="btn-print">Cetak Kuitansi</a>
        <a href="<?= BASE_URL ?>/pembayaran/konfirmasi.php" class="btn-print" style="background:#95a5a6; margin-left:10px;">Kembali</a>

        <div class="header">
            <h2>SMA Nusantara</h2>
            <p>Jl. Pendidikan No. 123, Kota Bandung | Telp: (022) 1234567 | Email: info@smanusantara.sch.id</p>
        </div>

        <div class="judul">KUITANSI PEMBAYARAN</div>

        <div class="info-baris">
            <div class="info-label">No. Kuitansi</div>
            <div class="info-value">: #<?= str_pad($pembayaran['id'], 6, '0', STR_PAD_LEFT) ?></div>
        </div>
        <div class="info-baris">
            <div class="info-label">Tanggal Pembayaran</div>
            <div class="info-value">: <?= date('d F Y', strtotime($pembayaran['tanggal_bayar'])) ?></div>
        </div>
        <?php if ($pembayaran['tanggal_konfirmasi']) : ?>
        <div class="info-baris">
            <div class="info-label">Tanggal Konfirmasi</div>
            <div class="info-value">: <?= date('d F Y', strtotime($pembayaran['tanggal_konfirmasi'])) ?></div>
        </div>
        <?php endif; ?>

        <br>

        <div class="info-baris">
            <div class="info-label2">Nama Siswa</div>
            <div class="info-value2">: <?= htmlspecialchars($pembayaran['nama_siswa']) ?></div>
        </div>
        <div class="info-baris">
            <div class="info-label2">Kelas</div>
            <div class="info-value2">: <?= htmlspecialchars($pembayaran['namakelas'] ?? '-') ?> (<?= htmlspecialchars($pembayaran['tingkat'] ?? '-') ?>)</div>
        </div>
        <div class="info-baris">
            <div class="info-label2">Tahun Ajaran</div>
            <div class="info-value2">: <?= htmlspecialchars($pembayaran['tahunajaran'] ?? '-') ?></div>
        </div>

        <table class="detail">
            <tr>
                <th style="width:40px;">No</th>
                <th>Jenis Pembayaran</th>
                <th style="width:150px;">Nominal</th>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td><?= htmlspecialchars($pembayaran['nama_biaya']) ?></td>
                <td class="nominal">Rp <?= number_format($pembayaran['nominal_bayar'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right; font-weight:bold;">Total Pembayaran</td>
                <td class="nominal" style="font-size:15px;">Rp <?= number_format($pembayaran['nominal_bayar'], 0, ',', '.') ?></td>
            </tr>
        </table>

        <div class="terbilang">
            Terbilang: <strong><?= terbilang($pembayaran['nominal_bayar']) ?> Rupiah</strong>
        </div>

        <div class="info-baris">
            <div class="info-label">Metode Pembayaran</div>
            <div class="info-value">: <?= $metodeLabel[$pembayaran['metode_bayar']] ?? $pembayaran['metode_bayar'] ?></div>
        </div>
        <div class="info-baris">
            <div class="info-label">Keterangan</div>
            <div class="info-value">: <?= htmlspecialchars($pembayaran['keterangan'] ?: '-') ?></div>
        </div>
        <div class="info-baris">
            <div class="info-label">Status</div>
            <div class="info-value">: <strong>LUNAS</strong></div>
        </div>

        <div class="tanda-tangan">
            <div class="tt">
                <div class="garis"></div>
                <p><strong>Bendahara</strong></p>
            </div>
            <div class="tt">
                <div class="garis"></div>
                <p><strong>Orang Tua/Wali</strong></p>
            </div>
            <div class="tt">
                <div class="garis"></div>
                <p><strong>Kepala Sekolah</strong></p>
            </div>
        </div>

        <div class="footer">
            <p>Kuitansi ini merupakan bukti pembayaran yang sah. Harap disimpan dengan baik.</p>
        </div>
    </div>
</body>
</html>
