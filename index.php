<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dana Operasional UBSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Fungsi sinkronisasi nilai Jumlah Saldo -> Debit
        function syncDebit() {
            let saldo = document.getElementById('input_jumlah_saldo').value;
            document.getElementById('input_debit_disabled').value = saldo;
        }
    </script>
</head>
<body class="bg-light">

<div class="container-fluid mt-4 mb-5 px-4">
    <div class="text-center mb-4">
        <h4>LAPORAN DANA OPERASIONAL MOBIL B 2649 TBW</h4>
        <h5>UNIVERSITAS BINA SARANA INFORMATIKA TASIKMALAYA</h5>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white p-0">
            <ul class="nav nav-tabs border-0" id="formTabs">
                <li class="nav-item">
                    <button class="nav-link active fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#tab-penerimaan">📥 1. Form Penerimaan Saldo Kartu</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#tab-pengeluaran">📤 2. Form Pengeluaran Operasional</button>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content p-4">
            
            <div class="tab-pane fade show active" id="tab-penerimaan">
                <form action="simpan.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="jenis" value="penerimaan">
                    <div class="row">
                        <div class="col-md-3 mb-3"><label>Bulan Pengiriman Kartu</label><input type="month" name="bulan_pengiriman" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>Tanggal Terima</label><input type="date" name="tanggal" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>No Kartu</label><input type="text" name="no_kartu" class="form-control" placeholder="0145 0082 0139 5927" required></div>
                        <div class="col-md-3 mb-3"><label>Diserahkan Oleh</label><input type="text" name="diserahkan_oleh" class="form-control" required></div>
                        
                        <div class="col-md-6 mb-3"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" value="Diterima Kartu Flash Mobil Operasional KK B 2649 TBW" required></div>
                        
                        <div class="col-md-3 mb-3"><label>Jumlah Saldo (Rp)</label><input type="number" name="jumlah_saldo" id="input_jumlah_saldo" class="form-control" oninput="syncDebit()" required></div>
                        <div class="col-md-3 mb-3"><label>Debit (Rp) - <i>Otomatis</i></label><input type="number" id="input_debit_disabled" class="form-control bg-light" disabled></div>
                        
                        <div class="col-md-6 mb-3"><label>Upload Foto Kartu</label><input type="file" name="foto_kartu" class="form-control" accept="image/*"></div>
                        <div class="col-md-6 mb-3"><label>Upload Bukti Saldo (M-Banking)</label><input type="file" name="foto_saldo" class="form-control" accept="image/*"></div>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Penerimaan</button>
                </form>
            </div>

            <div class="tab-pane fade" id="tab-pengeluaran">
                <form action="simpan.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="jenis" value="pengeluaran">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Bulan Pengiriman Kartu</label>
                            <select name="bulan_pengiriman_combo" class="form-select" required>
                                <option value="">-- Pilih Bulan --</option>
                                <?php
                                $q_bln = mysqli_query($conn, "SELECT DISTINCT bulan_pengiriman FROM laporan WHERE bulan_pengiriman != '' AND bulan_pengiriman IS NOT NULL ORDER BY bulan_pengiriman DESC");
                                while($rb = mysqli_fetch_assoc($q_bln)){
                                    echo "<option value='".$rb['bulan_pengiriman']."'>".$rb['bulan_pengiriman']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Tanggal Pengeluaran</label><input type="date" name="tanggal" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Keterangan Pengeluaran</label><input type="text" name="keterangan" class="form-control" placeholder="Contoh: Isi Bensin untuk Sosialisasi..." required></div>
                        
                        <div class="col-md-3 mb-3"><label>Kredit / Keluar (Rp)</label><input type="number" name="kredit" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Upload Struk Bensin/Bukti</label><input type="file" name="gambar" class="form-control" accept="image/*" required></div>
                    </div>
                    <button type="submit" class="btn btn-danger">Simpan Pengeluaran</button>
                </form>
            </div>
            
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Grid Data Operasional</h5>
            
            <form action="export_word.php" method="GET" class="d-flex m-0" target="_blank">
                <select name="filter_bulan" class="form-select form-select-sm me-2" required>
                    <option value="">-- Cetak Berdasarkan Bulan --</option>
                    <?php
                    $q_bln2 = mysqli_query($conn, "SELECT DISTINCT bulan_pengiriman FROM laporan WHERE bulan_pengiriman != '' AND bulan_pengiriman IS NOT NULL ORDER BY bulan_pengiriman DESC");
                    while($rb2 = mysqli_fetch_assoc($q_bln2)){
                        echo "<option value='".$rb2['bulan_pengiriman']."'>".$rb2['bulan_pengiriman']."</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-warning btn-sm fw-bold text-nowrap me-2">Export Laporan ke Word</button>
                <button type="submit" formaction="export_pdf.php" class="btn btn-danger btn-sm fw-bold text-nowrap me-2">Cetak PDF</button>
                <button type="submit" formaction="template_kirim_email.php" class="btn btn-info btn-sm fw-bold text-nowrap text-white">Template Email</button>
            </form>

        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle" style="font-size: 13px;">
                <thead class="table-secondary text-center">
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>BLN KARTU</th>
                        <th>NO KARTU</th>
                        <th>DISERAHKAN OLEH</th>
                        <th>KETERANGAN</th>
                        <th>JMLH SALDO (DEBET)</th>
                        <th>KREDIT</th>
                        <th>SALDO AKHIR</th>
                        <th>FILE TERSIMPAN</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT * FROM laporan ORDER BY id ASC");
                    while($row = mysqli_fetch_assoc($query)){
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                        <td class="text-center"><?= !empty($row['bulan_pengiriman']) ? $row['bulan_pengiriman'] : '-'; ?></td>
                        <td><?= htmlspecialchars($row['no_kartu'] ?: '-'); ?></td>
                        <td><?= htmlspecialchars($row['diserahkan_oleh'] ?: '-'); ?></td>
                        <td><?= htmlspecialchars($row['keterangan']); ?></td>
                        <td class="text-end text-success">Rp <?= number_format($row['debet'],0,',','.'); ?></td>
                        <td class="text-end text-danger">Rp <?= number_format($row['kredit'],0,',','.'); ?></td>
                        <td class="text-end fw-bold">Rp <?= number_format($row['saldo'],0,',','.'); ?></td>
                        <td class="text-center">
                            <?php if(!empty($row['foto_kartu'])) echo "<span class='badge bg-info'>Foto Kartu</span> "; ?>
                            <?php if(!empty($row['foto_saldo'])) echo "<span class='badge bg-primary'>Bukti Saldo</span> "; ?>
                            <?php if(!empty($row['gambar'])) echo "<span class='badge bg-warning text-dark'>Struk Bensin</span>"; ?>
                        </td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm mb-1">Edit</a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Hapus data?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>