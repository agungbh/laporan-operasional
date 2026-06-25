<?php
$host = "localhost";
$user = "root";       
$pass = ""; 
$db   = "db_laporan_operasional";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function recalculate_saldo($conn) {
    // Tambahkan pemanggilan kolom no_kartu pada query
    $query = mysqli_query($conn, "SELECT id, debet, kredit, no_kartu FROM laporan ORDER BY tanggal ASC, id ASC");
    $saldo = 0;
    
    while($row = mysqli_fetch_assoc($query)) {
        
        // Cek apakah field no_kartu ada isinya.
        // Jika ada, ini adalah data Penerimaan Saldo awal (Kartu Baru).
        if (!empty(trim($row['no_kartu']))) {
            // Reset saldo awal agar sesuai dengan jumlah uang di kartu baru,
            // tanpa menjumlahkan sisa saldo dari operasional kartu bulan sebelumnya.
            $saldo = $row['debet'] - $row['kredit'];
        } else {
            // Jika no_kartu kosong (artinya ini adalah transaksi Pengeluaran/Struk),
            // maka saldo dihitung berlanjut dari baris sebelumnya.
            $saldo = $saldo + $row['debet'] - $row['kredit'];
        }
        
        // Simpan update saldo ke database
        mysqli_query($conn, "UPDATE laporan SET saldo = '$saldo' WHERE id = '".$row['id']."'");
    }
}
?>