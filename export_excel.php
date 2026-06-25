<?php
include 'koneksi.php';

// Ambil bulan dari Filter
$filter_bulan = isset($_GET['filter_bulan']) ? $_GET['filter_bulan'] : '';
if($filter_bulan == ''){ die("Pilih bulan terlebih dahulu di halaman index!"); }

// Konversi Nama Bulan Indonesia dari format 'mm-YYYY'
$bulan_indo = array(
    1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL', 5 => 'MEI', 6 => 'JUNI', 
    7 => 'JULI', 8 => 'AGUSTUS', 9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
);
$arr_bln = explode('-', $filter_bulan);
$bln_angka = (int)$arr_bln[0];
$thn_angka = $arr_bln[1];

// Judul Bulan (Contoh: APRIL 2026)
$head_title_format = $bulan_indo[$bln_angka] . " " . $thn_angka;
$head_title_normal = ucwords(strtolower($head_title_format));

// Cari No Kartu
$q_head = mysqli_query($conn, "SELECT no_kartu FROM laporan WHERE bulan_pengiriman = '$filter_bulan' AND no_kartu != '' LIMIT 1");
$head_kartu = ".......................";
if($r_head = mysqli_fetch_assoc($q_head)){ $head_kartu = $r_head['no_kartu']; }

// Pengaturan Header Unduhan File Excel
$filename = "Laporan_Operasional_B2649TBW_" . $filter_bulan . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: "Calibri", sans-serif; 
            font-size: 11pt; 
            color: #000000; 
        }
        table { border-collapse: collapse; }
        .text-center { text-align: center; } 
        .text-right { text-align: right; } 
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .str-mode { mso-number-format:"\@"; } 

        /* CSS Styling untuk Border */
        .border-all {
            border: 1px solid #000000;
            mso-padding-alt: 4px 5px 4px 5px;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <table width="100%" style="border-collapse: collapse;">
        <col width="5%">  <col width="15%"> <col width="40%"> <col width="13%"> <col width="13%"> <col width="14%"> <tr>
            <td></td>
            <td rowspan="4" class="text-center" style="vertical-align: middle;">
                <img src="http://localhost/laporan_operasional/logo-bsi.png" width="80" height="80" alt="Logo">
            </td>
            <td class="text-center fw-bold" style="font-size: 12pt; mso-padding-alt: 5px; white-space: nowrap;">LAPORAN DANA OPERASIONAL MOBIL B 2649 TBW</td>
            <td></td> <td></td> <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center fw-bold" style="font-size: 12pt; mso-padding-alt: 5px; white-space: nowrap;">UNIVERSITAS BINA SARANA INFORMATIKA KAMPUS TASIKMALAYA</td>
            <td></td> <td></td> <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center fw-bold" style="font-size: 12pt; mso-padding-alt: 5px; white-space: nowrap;">BULAN <?= $head_title_format; ?></td>
            <td></td> <td></td> <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="height: 15px;"></td>
            <td></td> <td></td> <td></td>
        </tr>
        <tr>
            <td></td> <td></td> <td style="height: 15px;"></td> <td></td> <td></td> <td></td>
        </tr>

        <tr>
            <th rowspan="2" bgcolor="#D9D9D9" class="border-all text-center fw-bold" style="background-color: #D9D9D9; vertical-align: middle;">NO</th>
            <th rowspan="2" bgcolor="#D9D9D9" class="border-all text-center fw-bold" style="background-color: #D9D9D9; vertical-align: middle;">TANGGAL</th>
            <th rowspan="2" bgcolor="#D9D9D9" class="border-all text-center fw-bold" style="background-color: #D9D9D9; vertical-align: middle;">KETERANGAN</th>
            <th rowspan="2" bgcolor="#D9D9D9" class="border-all text-center fw-bold" style="background-color: #D9D9D9; vertical-align: middle;">DEBIT</th>
            <th rowspan="2" bgcolor="#D9D9D9" class="border-all text-center fw-bold" style="background-color: #D9D9D9; vertical-align: middle;">KREDIT</th>
            <th rowspan="2" bgcolor="#D9D9D9" class="border-all text-center fw-bold" style="background-color: #D9D9D9; vertical-align: middle;">SALDO</th>
        </tr>
        <tr>
            </tr>

        <tr>
            <td class="border-all">&nbsp;</td>
            <td class="border-all">&nbsp;</td>
            <td class="border-all text-left fw-bold">No Kartu: <span class="str-mode"><?= htmlspecialchars($head_kartu); ?></span></td>
            <td class="border-all">&nbsp;</td>
            <td class="border-all">&nbsp;</td>
            <td class="border-all">&nbsp;</td>
        </tr>

        <?php
        $no = 1; $total_debet = 0; $total_kredit = 0;
        $query = mysqli_query($conn, "SELECT * FROM laporan WHERE bulan_pengiriman = '$filter_bulan' ORDER BY tanggal ASC, id ASC");
        while($row = mysqli_fetch_assoc($query)){
            $total_debet += $row['debet']; 
            $total_kredit += $row['kredit'];
            
            // Penambahan "Rp. "
            $val_debet = ($row['debet'] == 0) ? '-' : 'Rp. ' . number_format($row['debet'],0,',','.');
            $val_kredit = ($row['kredit'] == 0) ? '-' : 'Rp. ' . number_format($row['kredit'],0,',','.');
            $val_saldo = 'Rp. ' . number_format($row['saldo'],0,',','.');
            
            $keterangan = nl2br(htmlspecialchars($row['keterangan']));
            
            echo "<tr>
                <td class='border-all text-center'>".$no++."</td>
                <td class='border-all text-center str-mode'>".date('d/m/Y', strtotime($row['tanggal']))."</td>
                <td class='border-all text-left' style='white-space: normal;'>".$keterangan."</td>
                <td class='border-all text-right str-mode'>".$val_debet."</td>
                <td class='border-all text-right str-mode'>".$val_kredit."</td>
                <td class='border-all text-right fw-bold str-mode'>".$val_saldo."</td>
            </tr>";
        }
        
        $total_saldo = $total_debet - $total_kredit;
        // Penambahan "Rp. " pada bagian total
        $disp_debet = ($total_debet == 0) ? "Rp. 0" : 'Rp. ' . number_format($total_debet,0,',','.');
        $disp_kredit = ($total_kredit == 0) ? "Rp. 0" : 'Rp. ' . number_format($total_kredit,0,',','.');
        $disp_saldo = ($total_saldo == 0) ? "Rp. 0" : 'Rp. ' . number_format($total_saldo,0,',','.');
        ?>

        <tr>
            <td bgcolor="#D9D9D9" class="border-all" style="background-color: #D9D9D9;">&nbsp;</td> 
            <td bgcolor="#D9D9D9" class="border-all" style="background-color: #D9D9D9;">&nbsp;</td>
            <td bgcolor="#D9D9D9" class="border-all" style="background-color: #D9D9D9;">&nbsp;</td>
            <td bgcolor="#D9D9D9" class="text-right fw-bold str-mode border-all" style="background-color: #D9D9D9; mso-padding-alt: 5px;"><?= $disp_debet; ?></td>
            <td bgcolor="#D9D9D9" class="text-right fw-bold str-mode border-all" style="background-color: #D9D9D9; mso-padding-alt: 5px;"><?= $disp_kredit; ?></td>
            <td bgcolor="#D9D9D9" class="text-right fw-bold str-mode border-all" style="background-color: #D9D9D9; mso-padding-alt: 5px;"><?= $disp_saldo; ?></td>
        </tr>

 
 
    </table>

</body>
</html>