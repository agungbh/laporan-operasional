<?php
include 'koneksi.php';

// Ambil bulan dari Filter
$filter_bulan = isset($_GET['filter_bulan']) ? $_GET['filter_bulan'] : '';
if($filter_bulan == ''){ die("Pilih bulan terlebih dahulu di halaman index!"); }

// Membuat subjek / nama judul format dinamis seperti export_pdf.php
$subjek = "LAPORAN DANA OPERASIONAL MOBIL B 2649 TBW UNIVERSITAS BINA SARANA INFORMATIKA TASIKMALAYA BULAN " . strtoupper(date('F Y', strtotime("01-" . $filter_bulan)));

// =================== DATA EMAIL ===================
$tanggal_sekarang = date('d-m-Y');
$penerima_email = "dwi.astuti@bsi.ac.id, baku@bsi.ac.id, kampus_j1@bsi.ac.id";

$isi_email = "Tasikmalaya, " . $tanggal_sekarang . "\n" .
"Yth. Ibu Dewi Astuti, M.Kom.\n" .
"Kepala BAKU Universitas Bina Sarana Informatika\n\n" .
"Dengan hormat,\n" .
"Bersama ini saya sampaikan " . $subjek . ".\n\n" .
"Hormat saya,\n\n" .
"Agung Baitul Hikmah, M.Kom.\n" .
"Kepala Kampus BSI Tasikmalaya";

// =================== DATA WHATSAPP ===================
// Nomor tujuan (format wa.me menggunakan kode negara 62 tanpa +)
$no_wa = "6281386200824"; 

$isi_wa = "Assalamualaikum Warahmatullahi Wabarakatuh, Mbak Kintan.\n\n" .
"Mohon izin menginformasikan bahwa " . $subjek . " telah selesai dan sudah saya sampaikan melalui email baku@bsi.ac.id. Untuk bukti fisik administrasi UBSI Tasikmalaya akan segera kirimkan.\n\n" .
"Terima kasih atas perhatian dan bantuannya, Mba Kintan.\n\n" .
"Wassalam,\n\n" .
"Agung Baitul Hikmah\n" .
"KK UBSI Tasikmalaya";

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Kirim Email & WhatsApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Fungsi untuk menyalin isi teks ke clipboard
        function copyText(elementId, type) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("Isi teks " + type + " berhasil disalin!");
        }

        // Fungsi Preview Gambar Screenshot
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview_screenshot');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body class="bg-light">

<div class="container-fluid mt-5 mb-5 px-4" style="max-width: 1200px;">
    <div class="text-center mb-4">
        <h4 class="fw-bold">DRAF TEMPLATE PELAPORAN DANA (EMAIL & WA)</h4>
        <h5 class="text-secondary">UBSI KAMPUS TASIKMALAYA</h5>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 fw-bold">📧 Template Kirim Email</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Penerima (To):</label>
                        <input type="text" class="form-control bg-white" value="<?= htmlspecialchars($penerima_email); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Subjek Email:</label>
                        <input type="text" class="form-control bg-white" value="<?= htmlspecialchars($subjek); ?>" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Isi Pesan Email:</label>
                        <textarea id="text_email" class="form-control bg-white" rows="12" style="font-family: 'Courier New', Courier, monospace;" readonly><?= htmlspecialchars($isi_email); ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3">
                        <button onclick="copyText('text_email', 'Email')" class="btn btn-outline-info fw-bold">📋 Salin Email</button>
                        <a href="mailto:<?= $penerima_email; ?>?subject=<?= rawurlencode($subjek); ?>&body=<?= rawurlencode($isi_email); ?>" class="btn btn-info fw-bold text-white" target="_blank">✉️ Buka App Email</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">💬 Template WhatsApp (Mbak Kintan)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Nomor Tujuan WA:</label>
                        <input type="text" class="form-control bg-white fw-bold text-success" value="+62 813-8620-0824" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Isi Pesan WhatsApp:</label>
                        <textarea id="text_wa" class="form-control bg-white" rows="12" style="font-family: 'Courier New', Courier, monospace;" readonly><?= htmlspecialchars($isi_wa); ?></textarea>
                    </div>
                    
                    <div class="mb-4 border p-3 rounded bg-light">
                        <label class="form-label fw-bold text-dark">Upload Bukti Screenshot Email:</label>
                        <input type="file" id="upload_screenshot" class="form-control mb-2" accept="image/*" onchange="previewImage(event)">
                        
                        <div class="text-center mt-2">
                            <img id="preview_screenshot" class="img-thumbnail shadow-sm" style="display:none; max-height: 150px; margin: 0 auto;">
                        </div>

                        <div class="alert alert-warning mt-2 mb-0 p-2" style="font-size: 12px;">
                            <strong>ℹ️ Info:</strong> API WhatsApp tidak dapat melampirkan gambar otomatis via tautan klik. Harap lakukan <strong>Copy (Salin)</strong> pada gambar asli Anda, lalu klik "Kirim via WA" di bawah, dan <strong>Paste (Tempel)</strong> manual gambar tersebut di kolom *chat* WhatsApp.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <button onclick="copyText('text_wa', 'WhatsApp')" class="btn btn-outline-success fw-bold">📋 Salin Teks WA</button>
                        <a href="https://wa.me/<?= $no_wa; ?>?text=<?= rawurlencode($isi_wa); ?>" class="btn btn-success fw-bold text-white" target="_blank">💬 Kirim via WA</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-secondary px-5 fw-bold">🔙 Kembali ke Laporan Utama</a>
    </div>

</div>

</body>
</html>