<?php
include 'koneksi.php';
$id = $_GET['id'];

// 1. Ambil data nama file gambar yang terhubung dengan ID yang mau dihapus
$query = mysqli_query($conn, "SELECT foto_kartu, foto_saldo, gambar FROM laporan WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if($data){
    // 2. Hapus file fisik gambar dari folder uploads jika filenya ada
    if(!empty($data['foto_kartu']) && file_exists("uploads/".$data['foto_kartu'])) { 
        unlink("uploads/".$data['foto_kartu']); 
    }
    if(!empty($data['foto_saldo']) && file_exists("uploads/".$data['foto_saldo'])) { 
        unlink("uploads/".$data['foto_saldo']); 
    }
    if(!empty($data['gambar']) && file_exists("uploads/".$data['gambar'])) { 
        unlink("uploads/".$data['gambar']); 
    }
}

// 3. Hapus data record dari database
mysqli_query($conn, "DELETE FROM laporan WHERE id='$id'");

// 4. Hitung ulang running balance (saldo) agar kembali rapi susunannya
recalculate_saldo($conn);

echo "<script>alert('Data berhasil dihapus beserta lampirannya!'); window.location='index.php';</script>";
?>