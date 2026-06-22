<?php
include 'koneksi.php';

function imgBase64($path, $width) {
    if(file_exists($path) && is_file($path)) {
        $data = base64_encode(file_get_contents($path));
        $mime = mime_content_type($path);
        return "<img src='data:$mime;base64,$data' width='$width' style='border: 1px solid #ccc; margin-bottom: 10px;'>";
    }
    return "<i style='color:red;'>[Gambar tidak ditemukan]</i>";
}

// Cari Header & Bulan Format (mm-yyyy dan mm YYYY)
$q_head = mysqli_query($conn, "SELECT bulan_pengiriman, no_kartu FROM laporan WHERE bulan_pengiriman != '' AND no_kartu != '' LIMIT 1");
$head_file_format = "mm-yyyy"; 
$head_title_format = "mm YYYY"; 
$head_kartu = ".......................";

if($r_head = mysqli_fetch_assoc($q_head)){
    $raw_bln = $r_head['bulan_pengiriman']; // format dari DB: 06-2026
    $head_file_format = $raw_bln;
    $head_title_format = str_replace("-", " ", $raw_bln); // menjadi: 06 2026
    $head_kartu = $r_head['no_kartu'];
}

// 1. SET NAMA FILE DOWNLOAD DINAMIS
$filename = "Laporan_Operasional_B2649TBW_" . $head_file_format . ".doc";

header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=\"$filename\"");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 portrait; margin: 2cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; color: #000; }
        table.tabel-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.tabel-data, table.tabel-data th, table.tabel-data td { border: 1px solid black; }
        table.tabel-data th { background-color: #f2f2f2; padding: 6px; text-align: center; }
        table.tabel-data td { padding: 6px; vertical-align: middle; }
        .text-center { text-align: center; } .text-right { text-align: right; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <table style="width: 100%; border: none; margin-bottom: 20px;">
        <tr>
            <td style="width: 15%; border: none; text-align: left; vertical-align: middle;">
                <?= imgBase64('uploads/logo-bsi.png', 100); ?>
            </td>
            <td style="width: 85%; border: none; text-align: center; vertical-align: middle;">
                <b style="font-size: 14pt; line-height: 1.5;">
                    LAPORAN DANA OPERASIONAL MOBIL B 2649 TBW<br>
                    UNIVERSITAS BINA SARANA INFORMATIKA TASIKMALAYA<br>
                    BULAN <?= htmlspecialchars($head_title_format); ?>
                </b>
            </td>
        </tr>
    </table>

    <p style="font-weight: bold;">No Kartu: <?= htmlspecialchars($head_kartu); ?></p>

    <table class="tabel-data">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="12%">TANGGAL</th>
                <th width="43%">KETERANGAN</th>
                <th width="13%">DEBET</th>
                <th width="13%">KREDIT</th>
                <th width="14%">SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1; $total_debet = 0; $total_kredit = 0;
            $query = mysqli_query($conn, "SELECT * FROM laporan ORDER BY tanggal ASC, id ASC");
            while($row = mysqli_fetch_assoc($query)){
                $total_debet += $row['debet'];
                $total_kredit += $row['kredit'];

                echo "<tr>
                    <td class='text-center'>".$no++."</td>
                    <td class='text-center'>".date('d-m-Y', strtotime($row['tanggal']))."</td>
                    <td>".htmlspecialchars($row['keterangan'])."</td>
                    <td class='text-right'>".number_format($row['debet'],0,',','.')."</td>
                    <td class='text-right'>".number_format($row['kredit'],0,',','.')."</td>
                    <td class='text-right'><strong>".number_format($row['saldo'],0,',','.')."</strong></td>
                </tr>";
            }
            $total_saldo = $total_debet - $total_kredit;
            ?>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-right"><?= number_format($total_debet,0,',','.'); ?></td>
                <td class="text-right"><?= number_format($total_kredit,0,',','.'); ?></td>
                <td class="text-right" style="background-color: #e6ffe6;"><?= number_format($total_saldo,0,',','.'); ?></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 60%; border: none;"></td>
            <td style="width: 40%; border: none; text-align: center;">
                Tasikmalaya, <?= date('d-m-Y'); ?><br><br>
                <?= imgBase64('uploads/ttd_stempel-removebg-preview.png', 130); ?><br><br>
                <strong>Agung Baitul Hikmah, S.Kom, M.Kom</strong><br>
                Kepala Kampus UBSI Tasikmalaya
            </td>
        </tr>
    </table>

    <div class="page-break">
        <h3 style="text-align: center; text-decoration: underline; margin-bottom: 30px;">LAMPIRAN</h3>
        
        <p style="font-weight: bold;">1. Lampiran Foto Kartu Flash</p>
        <?php
        $q_kartu = mysqli_query($conn, "SELECT foto_kartu FROM laporan WHERE foto_kartu != '' LIMIT 1");
        if($r_kartu = mysqli_fetch_assoc($q_kartu)){
            echo imgBase64('uploads/'.$r_kartu['foto_kartu'], 350);
        } else { echo "<p><i>Tidak ada lampiran foto kartu</i></p>"; }
        ?>
        <br><br>

        <p style="font-weight: bold;">2. Lampiran Foto Saldo Kartu Flash</p>
        <?php
        $q_saldo = mysqli_query($conn, "SELECT foto_saldo FROM laporan WHERE foto_saldo != '' LIMIT 1");
        if($r_saldo = mysqli_fetch_assoc($q_saldo)){
            echo imgBase64('uploads/'.$r_saldo['foto_saldo'], 350);
        } else { echo "<p><i>Tidak ada lampiran foto saldo</i></p>"; }
        ?>
        <br><br>

        <p style="font-weight: bold;">3. Lampiran Struk Bensin</p>
        <?php
        $q_struk = mysqli_query($conn, "SELECT tanggal, gambar FROM laporan WHERE gambar != '' ORDER BY tanggal ASC");
        $ada_struk = false;
        while($r_struk = mysqli_fetch_assoc($q_struk)){
            $ada_struk = true;
            echo imgBase64('uploads/'.$r_struk['gambar'], 350);
            echo "<br><span style='font-style: italic; font-weight: bold;'>Struk Bensin - ".date('d-m-Y', strtotime($r_struk['tanggal']))."</span><br><br><br>";
        }
        if(!$ada_struk){ echo "<p><i>Tidak ada lampiran struk bensin</i></p>"; }
        ?>
    </div>

</body>
</html>