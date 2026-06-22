<?php 
include 'koneksi.php'; 
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM laporan WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Operasional UBSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid mt-4 mb-5 px-4">
    <div class="text-center mb-4">
        <h4 class="fw-bold">EDIT LAPORAN DANA OPERASIONAL</h4>
        <h5 class="text-secondary">UNIVERSITAS BINA SARANA INFORMATIKA TASIKMALAYA</h5>
    </div>

    <div class="card shadow-sm mx-auto border-0" style="max-width: 1000px;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold">Form Edit Data Transaksi Operasional</h5>
        </div>
        <div class="card-body p-4">
            <form action="update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                
                <input type="hidden" name="old_foto_kartu" value="<?= htmlspecialchars($data['foto_kartu']); ?>">
                <input type="hidden" name="old_foto_saldo" value="<?= htmlspecialchars($data['foto_saldo']); ?>">
                <input type="hidden" name="old_gambar" value="<?= htmlspecialchars($data['gambar']); ?>">

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= $data['tanggal']; ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Bulan Kartu (MM-YYYY)</label>
                        <input type="text" name="bulan_pengiriman" value="<?= htmlspecialchars($data['bulan_pengiriman']); ?>" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">No Kartu</label>
                        <input type="text" name="no_kartu" value="<?= htmlspecialchars($data['no_kartu']); ?>" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Diserahkan Oleh</label>
                        <input type="text" name="diserahkan_oleh" value="<?= htmlspecialchars($data['diserahkan_oleh']); ?>" class="form-control">
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Keterangan Transaksi</label>
                        <input type="text" name="keterangan" value="<?= htmlspecialchars($data['keterangan']); ?>" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-success">Debet (Pemasukan / Jumlah Saldo)</label>
                        <input type="number" name="debet" value="<?= $data['debet']; ?>" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-danger">Kredit (Pengeluaran)</label>
                        <input type="number" name="kredit" value="<?= $data['kredit']; ?>" class="form-control">
                    </div>

                    <div class="col-md-12"><hr class="my-4"></div>
                    <h6 class="fw-bold mb-3">Manajemen Lampiran File</h6>

                    <div class="col-md-4 mb-4">
                        <label class="fw-bold text-primary">1. Ganti Foto Kartu</label>
                        <input type="file" name="foto_kartu" class="form-control mb-2" accept="image/*">
                        
                        <div class="p-2 border rounded bg-light text-center h-100" style="min-height: 140px;">
                            <p class="mb-1 small fw-bold">File Saat Ini:</p>
                            <?php if(!empty($data['foto_kartu'])): ?>
                                <img src="uploads/<?= $data['foto_kartu']; ?>" alt="Foto Kartu" class="img-thumbnail shadow-sm" style="height: 90px; object-fit: contain;">
                            <?php else: ?>
                                <span class="badge bg-secondary mt-2">Belum ada foto</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted d-block mt-1">*Kosongkan jika tidak ingin ganti</small>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="fw-bold text-primary">2. Ganti Bukti Saldo</label>
                        <input type="file" name="foto_saldo" class="form-control mb-2" accept="image/*">
                        
                        <div class="p-2 border rounded bg-light text-center h-100" style="min-height: 140px;">
                            <p class="mb-1 small fw-bold">File Saat Ini:</p>
                            <?php if(!empty($data['foto_saldo'])): ?>
                                <img src="uploads/<?= $data['foto_saldo']; ?>" alt="Bukti Saldo" class="img-thumbnail shadow-sm" style="height: 90px; object-fit: contain;">
                            <?php else: ?>
                                <span class="badge bg-secondary mt-2">Belum ada bukti saldo</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted d-block mt-1">*Kosongkan jika tidak ingin ganti</small>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="fw-bold text-primary">3. Ganti Struk Bensin / Bukti</label>
                        <input type="file" name="gambar" class="form-control mb-2" accept="image/*">
                        
                        <div class="p-2 border rounded bg-light text-center h-100" style="min-height: 140px;">
                            <p class="mb-1 small fw-bold">File Saat Ini:</p>
                            <?php if(!empty($data['gambar'])): ?>
                                <img src="uploads/<?= $data['gambar']; ?>" alt="Struk Bensin" class="img-thumbnail shadow-sm" style="height: 90px; object-fit: contain;">
                            <?php else: ?>
                                <span class="badge bg-secondary mt-2">Belum ada struk</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted d-block mt-1">*Kosongkan jika tidak ingin ganti</small>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="index.php" class="btn btn-secondary px-4 fw-bold me-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>