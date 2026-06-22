<?php
$host = "localhost";
$user = "root";       
$pass = ""; 
$db   = "db_laporan_operasional";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function recalculate_saldo($conn) {
    $query = mysqli_query($conn, "SELECT id, debet, kredit FROM laporan ORDER BY tanggal ASC, id ASC");
    $saldo = 0;
    while($row = mysqli_fetch_assoc($query)) {
        $saldo = $saldo + $row['debet'] - $row['kredit'];
        mysqli_query($conn, "UPDATE laporan SET saldo = '$saldo' WHERE id = '".$row['id']."'");
    }
}
?>