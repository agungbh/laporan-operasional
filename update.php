<?php
include 'koneksi.php';

$id               = $_POST['id'];
$tanggal          = $_POST['tanggal'];
$bulan_pengiriman = $_POST['bulan_pengiriman'];
$no_kartu         = $_POST['no_kartu'];
$diserahkan_oleh  = $_POST['diserahkan_oleh'];
$keterangan       = $_POST['keterangan'];
$debet            = (int)$_POST['debet'];
$kredit           = (int)$_POST['kredit'];
$jumlah_saldo     = $debet; // Kolom jumlah_saldo disinkronkan kembali dengan debet

// Fungsi untuk menangani Update File Gambar
function uploadFileUpdate($input_name, $old_file) {
    if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0){
        // Jika file lama ada, hapus file lama di server agar tidak menumpuk
        if(!empty($old_file) && file_exists("uploads/".$old_file)){
            unlink("uploads/".$old_file);
        }
        $ext = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
        $filename = $input_name . "_" . time() . "_" . uniqid() . "." . strtolower($ext);
        move_uploaded_file($_FILES[$input_name]['tmp_name'], "uploads/" . $filename);
        return $filename;
    }
    // Jika tidak ada file baru yang diupload, kembalikan nama file yang lama
    return $old_file;
}

$foto_kartu = uploadFileUpdate('foto_kartu', $_POST['old_foto_kartu']);
$foto_saldo = uploadFileUpdate('foto_saldo', $_POST['old_foto_saldo']);
$gambar     = uploadFileUpdate('gambar', $_POST['old_gambar']);

// Update ke Database menggunakan Prepared Statement
$stmt = $conn->prepare("UPDATE laporan SET tanggal=?, bulan_pengiriman=?, no_kartu=?, keterangan=?, jumlah_saldo=?, debet=?, kredit=?, diserahkan_oleh=?, foto_kartu=?, foto_saldo=?, gambar=? WHERE id=?");
$stmt->bind_param("ssssiiissssi", $tanggal, $bulan_pengiriman, $no_kartu, $keterangan, $jumlah_saldo, $debet, $kredit, $diserahkan_oleh, $foto_kartu, $foto_saldo, $gambar, $id);

if($stmt->execute()){
    recalculate_saldo($conn); // Hitung ulang saldo setelah nominal diedit
    echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui data!'); window.location='edit.php?id=$id';</script>";
}
?>