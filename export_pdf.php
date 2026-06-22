<?php
include 'koneksi.php';

// Ambil bulan dari Filter
$filter_bulan = isset($_GET['filter_bulan']) ? $_GET['filter_bulan'] : '';
if($filter_bulan == ''){ die("Pilih bulan terlebih dahulu di halaman index!"); }

// Fungsi rendering gambar dengan CSS pembatas margin presisi untuk kertas A4
function imgBase64($path) {
    if(file_exists($path) && is_file($path)) {
        $data = base64_encode(file_get_contents($path));
        $mime = mime_content_type($path);
        // max-width 100% dan max-height 19cm memastikan foto tidak melebihi area aman kertas
        return "<img src='data:$mime;base64,$data' style='max-width: 100%; max-height: 19cm; width: auto; height: auto; border: 1px solid #555; object-fit: contain; margin-top: 10px;'>";
    }
    return "<i style='color:red; font-size: 10pt;'>[Gambar tidak ditemukan]</i>";
}

// Cari Header & No Kartu KHUSUS untuk bulan yang difilter
$q_head = mysqli_query($conn, "SELECT no_kartu FROM laporan WHERE bulan_pengiriman = '$filter_bulan' AND no_kartu != '' LIMIT 1");

// --- UPDATE LOGIKA FORMAT BULAN (date 'F Y') ---
// Mengubah '04-2026' menjadi '01-04-2026' lalu diconvert menjadi 'APRIL 2026'
$head_title_format = strtoupper(date('F Y', strtotime("01-" . $filter_bulan))); 
$head_kartu = ".......................";
if($r_head = mysqli_fetch_assoc($q_head)){
    $head_kartu = $r_head['no_kartu'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Operasional_B2649TBW_<?= $filter_bulan; ?></title>
    <style>
        /* Pengaturan Standar Kertas A4 Portrait untuk Cetak PDF */
        @page {
            size: A4 portrait;
            margin: 2.5cm 2cm 2.5cm 2cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            color: #000000;
            line-height: 1.4;
            background-color: #fff;
        }
        
        /* Desain Tabel Utama */
        table.tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        table.tabel-data th, table.tabel-data td {
            border: 1px solid #000000;
            padding: 8px 6px;
        }
        table.tabel-data th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        table.tabel-data td {
            vertical-align: middle;
            font-size: 10.5pt;
        }
        
        /* Mencegah baris tabel terpotong horizontal antar lembar kertas */
        tr {
            page-break-inside: avoid;
        }
        
        .text-center { text-align: center; } 
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        /* Mengatur agar kontainer lampiran benar-benar full satu halaman */
        .page-break {
            page-break-before: always;
            width: 100%;
            display: block;
            text-align: center;
            padding-top: 10px;
        }
        
        .title-header {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border: none; margin-bottom: 25px;">
        <tr>
            <td style="width: 15%; border: none; text-align: left; vertical-align: middle;">
                <?php 
                if(file_exists('uploads/logo-bsi.png')){
                    $dt = base64_encode(file_get_contents('uploads/logo-bsi.png'));
                    $mm = mime_content_type('uploads/logo-bsi.png');
                    echo "<img src='data:$mm;base64,$dt' width='90' style='border:none;'>";
                }
                ?>
            </td>
            <td style="width: 85%; border: none; text-align: center; vertical-align: middle;">
                <div class="title-header">
                    LAPORAN DANA OPERASIONAL MOBIL B 2649 TBW<br>
                    UNIVERSITAS BINA SARANA INFORMATIKA TASIKMALAYA<br>
                    BULAN <?= htmlspecialchars($head_title_format); ?>
                </div>
            </td>
        </tr>
    </table>

    <p class="fw-bold" style="margin-bottom: 15px;">No Kartu: <?= htmlspecialchars($head_kartu); ?></p>

    <table class="tabel-data">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">TANGGAL</th>
                <th width="40%">KETERANGAN</th>
                <th width="13%">DEBET</th>
                <th width="13%">KREDIT</th>
                <th width="14%">SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1; $total_debet = 0; $total_kredit = 0;
            $query = mysqli_query($conn, "SELECT * FROM laporan WHERE bulan_pengiriman = '$filter_bulan' ORDER BY tanggal ASC, id ASC");
            while($row = mysqli_fetch_assoc($query)){
                $total_debet += $row['debet'];
                $total_kredit += $row['kredit'];

                echo "<tr>
                    <td class='text-center'>".$no++."</td>
                    <td class='text-center'>".date('d-m-Y', strtotime($row['tanggal']))."</td>
                    <td>".htmlspecialchars($row['keterangan'])."</td>
                    <td class='text-right'>".number_format($row['debet'],0,',','.')."</td>
                    <td class='text-right'>".number_format($row['kredit'],0,',','.')."</td>
                    <td class='text-right fw-bold'>".number_format($row['saldo'],0,',','.')."</td>
                </tr>";
            }
            $total_saldo = $total_debet - $total_kredit;
            ?>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-right"><?= number_format($total_debet,0,',','.'); ?></td>
                <td class="text-right"><?= number_format($total_kredit,0,',','.'); ?></td>
                <td class="text-right" style="background-color: #f2f2f2;"><?= number_format($total_saldo,0,',','.'); ?></td>
            </tr>
        </tbody>
    </table>

    <br>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 55%; border: none;"></td>
            <td style="width: 45%; border: none; text-align: center; vertical-align: top;">
                Tasikmalaya, <?= date('d-m-Y'); ?><br>
                <div style="margin-top: 5px; margin-bottom: 5px;">
                    <?php 
                    if(file_exists('uploads/ttd_stempel-removebg-preview.png')){
                        $dt_ttd = base64_encode(file_get_contents('uploads/ttd_stempel-removebg-preview.png'));
                        $mm_ttd = mime_content_type('uploads/ttd_stempel-removebg-preview.png');
                        echo "<img src='data:$mm_ttd;base64,$dt_ttd' width='140' style='border:none;'>";
                    }
                    ?>
                </div>
                <strong>Agung Baitul Hikmah, S.Kom, M.Kom</strong><br>
                Kepala Kampus UBSI Tasikmalaya
            </td>
        </tr>
    </table>

    <?php
    $q_lampiran = mysqli_query($conn, "SELECT tanggal, foto_kartu, foto_saldo, gambar FROM laporan WHERE bulan_pengiriman = '$filter_bulan' ORDER BY tanggal ASC");
    $kartu_tampil = false;
    $saldo_tampil = false;
    $arr_struk = [];

    while($rl = mysqli_fetch_assoc($q_lampiran)){
        if(!empty($rl['foto_kartu']) && !$kartu_tampil) { 
            $foto_kartu_val = $rl['foto_kartu']; $kartu_tampil = true; 
        }
        if(!empty($rl['foto_saldo']) && !$saldo_tampil) { 
            $foto_saldo_val = $rl['foto_saldo']; $saldo_tampil = true; 
        }
        if(!empty($rl['gambar'])) { 
            $arr_struk[] = ['tgl' => $rl['tanggal'], 'file' => $rl['gambar']]; 
        }
    }

    // 1. Lampiran Foto Kartu Flash
    if($kartu_tampil){
        echo "<div class='page-break'>";
        echo "<p class='text-center fw-bold' style='font-size: 14pt; text-decoration: underline; margin-bottom: 10px;'>LAMPIRAN</p>";
        echo "<p class='text-center fw-bold' style='margin-bottom: 10px; font-size: 12pt;'>1. Lampiran Foto Kartu Flash</p>";
        echo imgBase64('uploads/'.$foto_kartu_val); 
        echo "</div>";
    }

    // 2. Lampiran Foto Saldo
    if($saldo_tampil){
        echo "<div class='page-break'>";
        echo "<p class='text-center fw-bold' style='margin-bottom: 15px; font-size: 12pt;'>2. Lampiran Foto Saldo Kartu Flash</p>";
        echo imgBase64('uploads/'.$foto_saldo_val);
        echo "</div>";
    }

    // 3. Lampiran Struk Bensin (Satu per satu)
    if(count($arr_struk) > 0){
        foreach($arr_struk as $struk){
            echo "<div class='page-break'>";
            echo "<p class='text-center fw-bold' style='margin-bottom: 10px; font-size: 12pt;'>3. Lampiran Struk Bensin</p>";
            echo imgBase64('uploads/'.$struk['file']);
            echo "<br><p class='text-center fw-bold' style='font-style: italic; font-size: 12pt; margin-top: 10px;'>Struk Bensin - ".date('d-m-Y', strtotime($struk['tgl']))."</p>";
            echo "</div>";
        }
    }
    ?>

    <script>
        window.print();
    </script>
</body>
</html>