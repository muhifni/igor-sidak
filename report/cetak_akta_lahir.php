<?php
include "../inc/koneksi.php";

if (isset($_POST['btnCetak'])) {
    $id = $_POST['id_lahir'];
} else {
    die("ID Lahir tidak ditemukan.");
}

// Ambil data kelahiran
$sql_lahir = "
    SELECT 
        l.id_lahir,
        l.nik,
        l.nama,
        l.tgl_lh,
        l.jekel,
        l.anak_ke,
        k.no_kk,
        k.kepala,
        k.desa,
        k.kec,
        k.kab,
        k.prov,
        ibu.nama as nama_ibu,
        ayah.nama as nama_ayah
    FROM tb_lahir l
    LEFT JOIN tb_kk k ON l.id_kk = k.id_kk
    LEFT JOIN tb_pdd ibu ON l.id_ibu = ibu.id_pend
    LEFT JOIN tb_pdd ayah ON l.id_bapak = ayah.id_pend
    WHERE l.id_lahir = '$id'
";

$q_lahir = mysqli_query($koneksi, $sql_lahir);
$data = mysqli_fetch_assoc($q_lahir);

if (!$data) {
    die("Data Kelahiran tidak ditemukan.");
}

// Nama
$nama_anak = $data['nama'];

// Format tanggal
$tgl_lahir = date("d-m-Y", strtotime($data['tgl_lh']));
$tahun_lahir = date("Y", strtotime($data['tgl_lh']));
$tanggal_lahir = date("d", strtotime($data['tgl_lh']));
$bulan_lahir = date("n", strtotime($data['tgl_lh'])); // 1-12

// Tanggal dikeluarkan (hari ini)
$tgl_dikeluarkan = date("d F Y");
$bulan_indo = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];
$tgl_dikeluarkan = str_replace(array_keys($bulan_indo), array_values($bulan_indo), $tgl_dikeluarkan);

// Nomor registrasi
$no_reg = str_pad($data['id_lahir'], 4, '0', STR_PAD_LEFT);
$no_akta = 'AL.' . $data['id_lahir'];

// Konversi angka anak ke ke teks
function anakKeKeIndonesia($angka)
{
    $urutan_indo = [
        1 => 'SATU',
        2 => 'DUA',
        3 => 'TIGA',
        4 => 'EMPAT',
        5 => 'LIMA',
        6 => 'ENAM',
        7 => 'TUJUH',
        8 => 'DELAPAN',
        9 => 'SEMBILAN',
        10 => 'SEPULUH',
        11 => 'SEBELAS',
        12 => 'DUA BELAS',
        13 => 'TIGA BELAS',
        14 => 'EMPAT BELAS',
        15 => 'LIMA BELAS',
        16 => 'ENAM BELAS',
        17 => 'TUJUH BELAS',
        18 => 'DELAPAN BELAS',
        19 => 'SEMBILAN BELAS',
        20 => 'DUA PULUH'
    ];
    return isset($urutan_indo[$angka]) ? $urutan_indo[$angka] : $angka;
}

function anakKeKeInggris($angka)
{
    $urutan_eng = [
        1 => 'first',
        2 => 'second',
        3 => 'third',
        4 => 'fourth',
        5 => 'fifth',
        6 => 'sixth',
        7 => 'seventh',
        8 => 'eighth',
        9 => 'ninth',
        10 => 'tenth',
        11 => 'eleventh',
        12 => 'twelfth',
        13 => 'thirteenth',
        14 => 'fourteenth',
        15 => 'fifteenth',
        16 => 'sixteenth',
        17 => 'seventeenth',
        18 => 'eighteenth',
        19 => 'nineteenth',
        20 => 'twentieth'
    ];
    return isset($urutan_eng[$angka]) ? $urutan_eng[$angka] : $angka . 'th';
}

// Konversi tanggal (1-31) ke teks
function tanggalKeIndonesia($tgl)
{
    $satuan = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'DELAPAN', 'SEMBILAN'];
    $belasan = [
        'SEPULUH',
        'SEBELAS',
        'DUA BELAS',
        'TIGA BELAS',
        'EMPAT BELAS',
        'LIMA BELAS',
        'ENAM BELAS',
        'TUJUH BELAS',
        'DELAPAN BELAS',
        'SEMBILAN BELAS'
    ];

    if ($tgl < 10) {
        return $satuan[$tgl];
    } elseif ($tgl < 20) {
        return $belasan[$tgl - 10];
    } else {
        $puluhan = floor($tgl / 10);
        $sisa = $tgl % 10;
        return $satuan[$puluhan] . ' puluh' . ($sisa > 0 ? ' ' . $satuan[$sisa] : '');
    }
}

function tanggalKeInggris($tgl)
{
    $ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    $tens = ['', '', 'twenty', 'thirty'];
    $teens = [
        'ten',
        'eleven',
        'twelve',
        'thirteen',
        'fourteen',
        'fifteen',
        'sixteen',
        'seventeen',
        'eighteen',
        'nineteen'
    ];

    if ($tgl < 10) {
        return $ones[$tgl];
    } elseif ($tgl < 20) {
        return $teens[$tgl - 10];
    } else {
        $puluhan = floor($tgl / 10);
        $sisa = $tgl % 10;
        return $tens[$puluhan] . ($sisa > 0 ? '-' . $ones[$sisa] : '');
    }
}

// Konversi bulan ke teks
function bulanKeIndonesia($bulan)
{
    $bulan_indo = [
        1 => 'JANUARI',
        2 => 'FEBRUARI',
        3 => 'MARET',
        4 => 'APRIL',
        5 => 'MEI',
        6 => 'JUNI',
        7 => 'JULI',
        8 => 'AGUSTUS',
        9 => 'SEPTEMBER',
        10 => 'OKTOBER',
        11 => 'NOVEMBER',
        12 => 'DESEMBER'
    ];
    return strtoupper($bulan_indo[$bulan]);
}

function bulanKeInggris($bulan)
{
    $bulan_eng = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];
    return $bulan_eng[$bulan];
}

$anak_ke = anakKeKeIndonesia($data['anak_ke']);
$anak_ke_eng = anakKeKeInggris($data['anak_ke']);

// Jenis kelamin
$jekel_indo = $data['jekel'] == 'Laki-Laki' ? 'LAKI-LAKI' : 'PEREMPUAN';
$jekel_eng = $data['jekel'] == 'Laki-Laki' ? 'male' : 'female';

// Nama orang tua
$nama_ayah = $data['nama_ayah'] ?: '-';
$nama_ibu = $data['nama_ibu'] ?: '-';

// Konversi tahun ke teks Indonesia
function tahunKeIndonesia($tahun)
{
    $angka = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'DELAPAN', 'SEMBILAN'];
    $belasan = [
        'SEPULUH',
        'SEBELAS',
        'DUA BELAS',
        'TIGA BELAS',
        'EMPAT BELAS',
        'LIMA BELAS',
        'ENAM BELAS',
        'TUJUH BELAS',
        'DELAPAN BELAS',
        'SEMBILAN BELAS'
    ];

    $ribuan = floor($tahun / 1000);
    $ratusan = floor(($tahun % 1000) / 100);
    $puluhan = floor(($tahun % 100) / 10);
    $satuan = $tahun % 10;

    $hasil = '';

    // Ribuan
    if ($ribuan == 1) {
        $hasil .= 'SATU RIBU';
    } else if ($ribuan > 1) {
        $hasil .= $angka[$ribuan] . ' RIBU';
    }

    // Ratusan
    if ($ratusan > 0) {
        $hasil .= ' ';
        if ($ratusan == 1) {
            $hasil .= 'SATU RATUS';
        } else {
            $hasil .= $angka[$ratusan] . ' RATUS';
        }
    }

    // Puluhan dan satuan
    if ($puluhan == 1) {
        $hasil .= ' ' . $belasan[$satuan];
    } else {
        if ($puluhan > 0) {
            $hasil .= ' ' . $angka[$puluhan] . ' PULUH';
        }
        if ($satuan > 0) {
            $hasil .= ' ' . $angka[$satuan];
        }
    }

    return trim($hasil);
}

// Konversi tahun ke teks Inggris
function tahunKeInggris($tahun)
{
    $ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    $teens = [
        'ten',
        'eleven',
        'twelve',
        'thirteen',
        'fourteen',
        'fifteen',
        'sixteen',
        'seventeen',
        'eighteen',
        'nineteen'
    ];

    $ribuan = floor($tahun / 1000);
    $ratusan = floor(($tahun % 1000) / 100);
    $puluhan = floor(($tahun % 100) / 10);
    $satuan = $tahun % 10;

    $hasil = '';

    // Ribuan
    if ($ribuan > 0) {
        $hasil .= $ones[$ribuan] . ' thousand';
    }

    // Ratusan
    if ($ratusan > 0) {
        $hasil .= ' ' . $ones[$ratusan] . ' hundred';
    }

    // Puluhan dan satuan
    if ($puluhan == 1) {
        $hasil .= ' and ' . $teens[$satuan];
    } else {
        if ($puluhan > 0 || $ratusan > 0 || $ribuan > 0) {
            if ($satuan > 0) {
                $hasil .= ' and';
            }
        }
        if ($puluhan > 0) {
            $hasil .= ' ' . $tens[$puluhan];
        }
        if ($satuan > 0) {
            if ($puluhan > 0) {
                $hasil .= '-' . $ones[$satuan];
            } else {
                $hasil .= ' ' . $ones[$satuan];
            }
        }
    }

    return trim($hasil);
}

$tahun_teks_indo = tahunKeIndonesia($tahun_lahir);
$tahun_teks_eng = tahunKeInggris($tahun_lahir);

// Format tanggal lengkap dalam teks
$tgl_teks_indo = tanggalKeIndonesia($tanggal_lahir);
$bulan_teks_indo = bulanKeIndonesia($bulan_lahir);
$tanggal_lengkap_indo = $tgl_teks_indo . ' ' . $bulan_teks_indo . ' tahun ' . $tahun_teks_indo;

$tgl_teks_eng = tanggalKeInggris($tanggal_lahir);
$bulan_teks_eng = bulanKeInggris($bulan_lahir);
$tanggal_lengkap_eng = $tgl_teks_eng . ' of ' . $bulan_teks_eng . ' ' . $tahun_teks_eng;

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kutipan Akta Kelahiran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .akta-container {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .akta-container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            position: relative;
            overflow: hidden;
        }

        /* Border ornamental - mirip referensi */
        .border-frame {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 6px solid #4A90E2;
            border-image: repeating-linear-gradient(45deg,
                    #4A90E2,
                    #4A90E2 10px,
                    #81C784 10px,
                    #81C784 20px,
                    #FFD700 20px,
                    #FFD700 30px) 6;
            background: linear-gradient(to bottom, #e8f5e9 0%, #ffffff 50%, #e8f5e9 100%);
        }

        .border-inner {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 2px solid #81C784;
            background: white;
            padding: 15mm 20mm;
        }

        /* Header - Nomor Registrasi */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8mm;
            font-size: 9pt;
        }

        .header-left {
            text-align: left;
            line-height: 1.3;
        }

        .header-right {
            text-align: right;
            line-height: 1.3;
        }

        .header-left i,
        .header-right i {
            font-size: 8pt;
        }

        /* Logo Garuda */
        .logo-section {
            text-align: center;
            margin: 5mm 0;
        }

        .garuda-placeholder {
            width: 55px;
            height: 55px;
            margin: 0 auto;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        /* Title Section */
        .title-section {
            text-align: center;
            margin: 6mm 0;
        }

        .title-main {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 2px 0;
        }

        .title-sub {
            font-size: 9pt;
            font-style: italic;
            margin: 1px 0;
        }

        .title-nationality {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 3px 0;
        }

        .title-nationality span {
            border-bottom: 2px solid #000;
            padding: 0 8px;
        }

        .title-akta {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px 0;
            letter-spacing: 2px;
        }

        /* Content Section */
        .content {
            margin: 8mm 0;
            font-size: 10pt;
            line-height: 1.6;
        }

        .content-line {
            margin: 4px 0;
        }

        .label-en {
            font-style: italic;
            font-size: 8pt;
            color: #555;
        }

        .value-field {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 150px;
            padding: 0 3px;
            font-weight: normal;
        }

        .name-container {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 15px 0;
            width: 100%;
        }

        .name-container::before,
        .name-container::after {
            content: "";
            flex: 1;
            border-bottom: 2px dotted #000;
        }

        .name-text {
            padding: 0 20px;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .separator {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 8px 0;
        }

        .separator-en {
            font-style: italic;
            font-size: 9pt;
        }

        /* Footer - Issue Information */
        .footer-section {
            margin-top: 10mm;
            text-align: right;
        }

        .issue-info {
            font-size: 10pt;
            line-height: 1.6;
            margin-bottom: 3mm;
        }

        /* Signature Section */
        .signature-box {
            text-align: center;
            margin-top: 5mm;
        }

        .signature-title {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }

        .signature-space {
            margin: 15mm 0 3mm 0;
            border-bottom: 1px solid #000;
            width: 200px;
            display: inline-block;
        }

        .signature-name {
            font-size: 10pt;
            font-weight: bold;
        }

        .signature-nip {
            font-size: 9pt;
            margin-top: 2mm;
        }

        /* Stamp placeholder */
        .stamp-area {
            position: absolute;
            right: 35mm;
            bottom: 35mm;
            width: 80px;
            height: 80px;
            border: 2px solid rgba(74, 144, 226, 0.3);
            border-radius: 50%;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="akta-container">
        <div class="border-frame">
            <div class="border-inner">

                <!-- Header -->
                <div class="header">
                    <div class="header-left">
                        <i>Nomor Induk Kependudukan</i><br>
                        <i>Personnel Registration Number</i><br>
                        <strong><?= $no_reg ?></strong>
                    </div>
                    <div class="header-right">
                        <strong>No. Reg : <?= $tahun_lahir ?></strong><br>
                        <strong>No. <?= $no_akta ?></strong>
                    </div>
                </div>

                <!-- Logo -->
                <div class="logo-section">
                    <div class="garuda-placeholder" style="background: none; box-shadow: none;">
                        <img src="../dist/img/logo-garuda.png" alt="Garuda" style="width: 70px; height: auto;">
                    </div>
                </div>

                <!-- Title -->
                <div class="title-section">
                    <div class="title-main">Pencatatan Sipil</div>
                    <div class="title-sub">REGISTRY OFFICE</div>
                    <div class="title-nationality">
                        Warga Negara <span>Indonesia</span>
                    </div>
                    <div class="title-sub">NATIONALITY <i>Of INDONESIA</i></div>
                    <div class="title-akta">Kutipan Akta Kelahiran</div>
                    <div class="title-sub">EXCERPT OF BIRTH CERTIFICATE</div>
                </div>

                <!-- Content -->
                <div class="content">
                    <div class="content-line">
                        Berdasarkan Akta Kelahiran Nomor <span class="value-field"><?= $no_reg ?></span>
                    </div>
                    <div class="content-line">
                        <span class="label-en">By virtue of Birth Certificate Number</span>
                    </div>

                    <div class="content-line" style="margin-top: 6px;">
                        menurut <span class="value-field">stbld</span>
                    </div>
                    <div class="content-line">
                        <span class="label-en">in accordance with state gazette</span>
                    </div>

                    <div class="content-line" style="margin-top: 6px;">
                        bahwa di <span class="value-field"><?= htmlspecialchars($data['kab']) ?></span>
                        pada tanggal <span class="value-field"><?= $tgl_teks_indo ?> </span>
                    </div>
                    <div class="content-line">
                        <span class="label-en">that in</span>
                        <span class="label-en" style="margin-left: 175px;">on date <?= $tgl_teks_eng ?> </span>
                    </div>

                    <div class="content-line" style="margin-top: 6px;">
                        <span class="value-field"><?= $bulan_teks_indo ?></span> tahun <span
                            class="value-field"><?= $tahun_teks_indo ?></span> telah lahir:
                    </div>
                    <div class="content-line">
                        <span class="label-en"><?= $bulan_teks_eng ?></span>
                        <span class="label-en" style="margin-left: 110px;">on year <?= $tahun_teks_eng ?></span>
                    </div>

                    <div class="name-container">
                        <span class="name-text"><?= htmlspecialchars($nama_anak) ?></span>
                    </div>

                    <div class="content-line" style="margin-top: 6px;">
                        anak ke: <span class="value-field"><?= $anak_ke ?>, <?= $jekel_indo ?> DARI SUAMI ISTERI:
                            <?= $nama_ayah ?> dan <?= $nama_ibu ?></span>
                    </div>
                    <div class="content-line">
                        <span class="label-en">child no</span>
                        <span class="label-en" style="margin-left: 25px;"><?= $anak_ke_eng ?>, <?= $jekel_eng ?>
                            of the married
                            couple: <?= $nama_ayah ?> and <?= $nama_ibu ?></span>
                    </div>
                </div>

                <!-- Footer & Signature -->
                <div class="footer-section">
                    <div class="issue-info">
                        Kutipan ini dikeluarkan di <span
                            class="value-field"><?= htmlspecialchars($data['kab']) ?></span><br>
                        <span class="label-en" style="margin-right: 155px;">The excerpt is issued in</span>
                    </div>

                    <div class="issue-info">
                        pada tanggal <span class="value-field"><?= $tgl_dikeluarkan ?></span><br>
                        <span class="label-en" style="margin-right: 155px;">on date</span>
                    </div>

                    <div class="issue-info" style="margin-top: 4mm;">
                        Kepala <span class="value-field"><b>Dinas Kependudukan dan Pencatatan Sipil</b></span><br>
                    </div>

                    <!-- Signature -->
                    <!-- <div class="signature-box">
                        <div class="signature-title">
                            <?= strtoupper(htmlspecialchars($data['kab'])) ?>
                        </div>
                        <div class="signature-space"></div>
                        <div class="signature-name">
                            _______________________________
                        </div>
                        <div class="signature-nip">
                            <strong>NIP:</strong> ___________________________
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>