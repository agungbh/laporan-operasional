<?php
include 'koneksi.php';

$jenis      = $_POST['jenis'];
$tanggal    = $_POST['tanggal'];
$keterangan = $_POST['keterangan'];

// Fungsi untuk menangani upload file
function uploadFile($input_name) {
    if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0){
        $ext = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
        $filename = $input_name . "_" . time() . "_" . uniqid() . "." . strtolower($ext);
        move_uploaded_file($_FILES[$input_name]['tmp_name'], "uploads/" . $filename);
        return $filename;
    } 
    return "";
}

if ($jenis == 'penerimaan') {
    // Menangkap input dari Form 1 (Penerimaan)
    // Ubah format input type="month" (YYYY-MM) menjadi format (MM-YYYY)
    $bulan_pengiriman = !empty($_POST['bulan_pengiriman']) ? date('m-Y', strtotime($_POST['bulan_pengiriman'])) : '';
    $no_kartu         = $_POST['no_kartu'];
    $diserahkan_oleh  = $_POST['diserahkan_oleh'];
    $jumlah_saldo     = (int)$_POST['jumlah_saldo'];
    
    $debet  = $jumlah_saldo; // Debet diambil langsung dari jumlah saldo
    $kredit = 0;
    
    $foto_kartu = uploadFile('foto_kartu');
    $foto_saldo = uploadFile('foto_saldo');
    $gambar     = ""; // Kosongkan file struk untuk form ini
} else {
    // Menangkap input dari Form 2 (Pengeluaran)
    // Tangkap bulan dari Combo Box (Dropdown) yang nilainya sudah (MM-YYYY)
    $bulan_pengiriman = $_POST['bulan_pengiriman_combo']; 
    $no_kartu         = ""; 
    $diserahkan_oleh  = "";
    $jumlah_saldo     = 0; 
    $debet            = 0;
    $kredit           = !empty($_POST['kredit']) ? (int)$_POST['kredit'] : 0;
    
    $foto_kartu = ""; 
    $foto_saldo = "";
    $gambar     = uploadFile('gambar'); // Proses upload struk
}

// Simpan data ke database (Nilai saldo diisi 0 sementara)
$stmt = $conn->prepare("INSERT INTO laporan (tanggal, bulan_pengiriman, no_kartu, keterangan, jumlah_saldo, debet, kredit, saldo, diserahkan_oleh, foto_kartu, foto_saldo, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?)");
$stmt->bind_param("ssssiiissss", $tanggal, $bulan_pengiriman, $no_kartu, $keterangan, $jumlah_saldo, $debet, $kredit, $diserahkan_oleh, $foto_kartu, $foto_saldo, $gambar);

if($stmt->execute()){
    // Memanggil fungsi recalculate dari koneksi.php untuk mengurutkan dan menghitung ulang saldo otomatis
    recalculate_saldo($conn);
    echo "<script>alert('Data berhasil disimpan!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal menyimpan data!'); window.location='index.php';</script>";
}
?>