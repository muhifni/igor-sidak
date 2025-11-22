<?php
include "../inc/koneksi.php";

if (isset($_POST['btnCetak'])) {
    $id = $_POST['id_kk'];
} else {
    die("ID KK tidak ditemukan.");
}

// tanggal yang ditampilkan di bagian bawah KK
$tgl_dikeluarkan = date("d-m-Y");

// Ambil data KK + kepala keluarga
$sql_kk = "
    SELECT 
        k.id_kk,
        k.no_kk,
        k.kepala,
        k.id_kepala,
        k.desa,
        k.rt,
        k.rw,
        k.kec,
        k.kab,
        k.prov,
        p.nik AS nik_kepala,
        p.nama AS nama_kepala
    FROM tb_kk k
    LEFT JOIN tb_pdd p ON k.id_kepala = p.id_pend
    WHERE k.id_kk = '$id'
";

$q_kk = mysqli_query($koneksi, $sql_kk);
$data_kk = mysqli_fetch_assoc($q_kk);

if (!$data_kk) {
    die("Data KK tidak ditemukan.");
}

// Ambil data anggota keluarga
$sql_anggota = "
    SELECT 
        p.id_pend,
        p.nik,
        p.nama,
        p.tempat_lh,
        p.tgl_lh,
        p.jekel,
        p.agama,
        p.kawin,
        p.pekerjaan,
        p.status,
        a.hubungan
    FROM tb_anggota a
    INNER JOIN tb_pdd p ON a.id_pend = p.id_pend
    WHERE a.id_kk = '$id'
    ORDER BY a.id_anggota ASC
";
$q_anggota = mysqli_query($koneksi, $sql_anggota);

// Masukkan ke array agar bisa dipakai 2x (tabel atas & bawah)
$anggota = [];
while ($row = mysqli_fetch_assoc($q_anggota)) {
    $anggota[] = $row;
}

// Cek apakah kepala keluarga ada di data anggota
$kepalaSudahAda = false;
foreach ($anggota as $a) {
    if ($a['id_pend'] == $data_kk['id_kepala']) {
        $kepalaSudahAda = true;
        break;
    }
}

// Jika belum ada → tambahkan manual sebagai anggota pertama
if (!$kepalaSudahAda && $data_kk['id_kepala']) {

    $idkep = $data_kk['id_kepala'];

    $sql_kepala = "
        SELECT 
            p.id_pend,
            p.nik,
            p.nama,
            p.tempat_lh,
            p.tgl_lh,
            p.jekel,
            p.agama,
            p.kawin,
            p.pekerjaan,
            p.status
        FROM tb_pdd p
        WHERE p.id_pend = '$idkep'
    ";
    $q_kepala = mysqli_query($koneksi, $sql_kepala);
    $data_kepala = mysqli_fetch_assoc($q_kepala);

    if ($data_kepala) {
        // Tambahkan field hubungan, karena tidak ada di tb_pdd
        $data_kepala['hubungan'] = "Kepala Keluarga";

        // Masukkan ke awal array
        array_unshift($anggota, $data_kepala);
    }
}


// Pastikan maksimal 10 baris seperti form KK
$totalBaris = 5;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kartu Keluarga</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
        }

        /* A4 landscape */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        .kk-wrapper {
            width: 100%;
        }

        .header-kk {
            width: 100%;
            text-align: center;
            position: relative;
        }

        .logo-garuda {
            position: absolute;
            left: 0;
            top: 0;
        }

        .logo-garuda img {
            width: 70px;
        }

        .judul-kk {
            font-size: 22px;
            font-weight: bold;
        }

        .no-kk {
            font-size: 18px;
            font-weight: bold;
        }

        .info-atas {
            margin-top: 10px;
            width: 100%;
            font-size: 11px;
        }

        .info-atas td {
            vertical-align: top;
            padding: 2px 0;
        }

        .info-kiri {
            width: 50%;
        }

        .info-kanan {
            width: 50%;
        }

        .label {
            display: inline-block;
            width: 130px;
        }

        .table-kk {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-kk th,
        .table-kk td {
            border: 1px solid #000;
            padding: 2px 3px;
        }

        .table-kk th {
            text-align: center;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .footer-kk {
            margin-top: 15px;
            width: 100%;
            font-size: 11px;
        }

        .footer-kk td {
            vertical-align: top;
        }

        .qr-placeholder {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            text-align: center;
            font-size: 8px;
            line-height: 80px;
        }

        .small {
            font-size: 9px;
        }

        .mt-5 {
            margin-top: 5px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="kk-wrapper">
        <!-- HEADER -->
        <div class="header-kk">
            <div class="logo-garuda">
                <!-- ganti path gambar sesuai punya kamu -->
                <!-- <img src="img/garuda.png" alt="Garuda"> -->
            </div>
            <div>
                <div class="judul-kk">KARTU KELUARGA</div>
                <div class="no-kk">No. <?= htmlspecialchars($data_kk['no_kk']); ?></div>
            </div>
        </div>

        <!-- INFO KEPALA KELUARGA & ALAMAT -->
        <table class="info-atas">
            <tr>
                <td class="info-kiri">
                    <span class="label">Nama Kepala Keluarga</span> :
                    <?= htmlspecialchars($data_kk['nama_kepala'] ?: $data_kk['kepala']); ?><br>
                    <span class="label">Alamat</span> : <?= htmlspecialchars($data_kk['desa']); ?><br>
                    <span class="label">RT/RW</span> :
                    <?= htmlspecialchars($data_kk['rt']); ?>/<?= htmlspecialchars($data_kk['rw']); ?><br>
                    <span class="label">Kode Pos</span> : -
                </td>
                <td class="info-kanan">
                    <span class="label">Desa/Kelurahan</span> : <?= htmlspecialchars($data_kk['desa']); ?><br>
                    <span class="label">Kecamatan</span> : <?= htmlspecialchars($data_kk['kec']); ?><br>
                    <span class="label">Kabupaten/Kota</span> : <?= htmlspecialchars($data_kk['kab']); ?><br>
                    <span class="label">Provinsi</span> : <?= htmlspecialchars($data_kk['prov']); ?>
                </td>
            </tr>
        </table>

        <!-- TABEL BAGIAN ATAS -->
        <table class="table-kk mt-5">
            <tr>
                <th rowspan="2" style="width:20px;">No</th>
                <th rowspan="2" style="width:130px;">Nama Lengkap<br><span class="small">(1)</span></th>
                <th rowspan="2" style="width:110px;">NIK<br><span class="small">(2)</span></th>
                <th rowspan="2" style="width:40px;">Jenis<br>Kelamin<br><span class="small">(3)</span></th>
                <th rowspan="2" style="width:90px;">Tempat Lahir<br><span class="small">(4)</span></th>
                <th rowspan="2" style="width:80px;">Tanggal Lahir<br><span class="small">(5)</span></th>
                <th rowspan="2" style="width:70px;">Agama<br><span class="small">(6)</span></th>
                <th rowspan="2" style="width:90px;">Pendidikan<br><span class="small">(7)</span></th>
                <th rowspan="2" style="width:90px;">Jenis Pekerjaan<br><span class="small">(8)</span></th>
                <th rowspan="2" style="width:60px;">Golongan Darah<br><span class="small">(9)</span></th>
            </tr>
            <tr></tr>
            <!-- baris kedua header (kosong karena semua rowspan) -->
            <?php
            for ($i = 0; $i < $totalBaris; $i++) {
                $row = isset($anggota[$i]) ? $anggota[$i] : null;
                ?>
                <tr>
                    <td class="center"><?= $i + 1; ?></td>
                    <td><?= $row ? htmlspecialchars($row['nama']) : '&nbsp;'; ?></td>
                    <td><?= $row ? htmlspecialchars($row['nik']) : '&nbsp;'; ?></td>
                    <td class="center">
                        <?php
                        if ($row) {
                            // jekel di DB: LK/PR/Laki-Laki/Perempuan
                            echo ($row['jekel'] == 'LK' || $row['jekel'] == 'Laki-Laki') ? 'L' : 'P';
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </td>
                    <td><?= $row ? htmlspecialchars($row['tempat_lh']) : '&nbsp;'; ?></td>
                    <td class="center">
                        <?php
                        if ($row) {
                            echo date("d-m-Y", strtotime($row['tgl_lh']));
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </td>
                    <td><?= $row ? htmlspecialchars($row['agama']) : '&nbsp;'; ?></td>
                    <td><!-- pendidikan tidak ada di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                    <td><?= $row ? htmlspecialchars($row['pekerjaan']) : '&nbsp;'; ?></td>
                    <td class="center">
                        <!-- golongan darah tidak ada di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <!-- TABEL BAGIAN BAWAH -->
        <table class="table-kk mt-5">
            <tr>
                <th rowspan="2" style="width:20px;">No</th>
                <th rowspan="2" style="width:80px;">Status Perkawinan<br><span class="small">(10)</span></th>
                <th rowspan="2" style="width:90px;">Tanggal Perkawinan/<br>Perceraian<br><span class="small">(11)</span>
                </th>
                <th rowspan="2" style="width:110px;">Status Hubungan<br>Dalam Keluarga<br><span
                        class="small">(12)</span></th>
                <th rowspan="2" style="width:80px;">Kewarganegaraan<br><span class="small">(13)</span></th>
                <th colspan="2" style="width:140px;">Dokumen Imigrasi</th>
                <th colspan="2" style="width:180px;">Nama Orang Tua</th>
            </tr>
            <tr>
                <th style="width:70px;">No. Paspor<br><span class="small">(14)</span></th>
                <th style="width:70px;">No. KITAP<br><span class="small">(15)</span></th>
                <th style="width:90px;">Ayah<br><span class="small">(16)</span></th>
                <th style="width:90px;">Ibu<br><span class="small">(17)</span></th>
            </tr>
            <?php
            for ($i = 0; $i < $totalBaris; $i++) {
                $row = isset($anggota[$i]) ? $anggota[$i] : null;
                ?>
                <tr>
                    <td class="center"><?= $i + 1; ?></td>
                    <td><?= $row ? htmlspecialchars($row['kawin']) : '&nbsp;'; ?></td>
                    <td class="center">
                        <!-- tidak ada tanggal nikah/cerai di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                    <td><?= $row ? htmlspecialchars($row['hubungan']) : '&nbsp;'; ?></td>
                    <td class="center">
                        <!-- asumsi WNI, karena tidak ada kolom kewarganegaraan -->
                        <?= $row ? 'WNI' : '&nbsp;'; ?>
                    </td>
                    <td><!-- no paspor tidak ada di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                    <td><!-- no KITAP tidak ada di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                    <td><!-- nama ayah tidak ada di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                    <td><!-- nama ibu tidak ada di DB -->
                        <?= $row ? '-' : '&nbsp;'; ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <!-- FOOTER -->
        <table class="footer-kk mt-10">
            <tr>
                <td style="width:40%;">
                    Dikeluarkan Tanggal : <?= $tgl_dikeluarkan; ?>
                </td>
                <td style="width:30%; text-align:center;">
                    <div class="mt-20">
                        Kepala Keluarga<br><br><br><br>
                        <u><?= htmlspecialchars($data_kk['nama_kepala'] ?: $data_kk['kepala']); ?></u>
                    </div>
                </td>
                <td style="width:30%; text-align:right;">
                    <div>
                        KEPALA DINAS KEPENDUDUKAN DAN<br>
                        PENCATATAN SIPIL KABUPATEN <?= strtoupper(htmlspecialchars($data_kk['kab'])); ?><br><br><br><br>
                        <u>.......................................</u><br>
                        NIP. .............................
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align:right; padding-top:10px;">
                    <!-- <div class="qr-placeholder">QR CODE</div> -->
                </td>
            </tr>
        </table>

        <div class="small mt-10">
            Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang
            diterbitkan oleh Balai Sertifikat Elektronik (BSrE), BSSN.
        </div>
    </div>

</body>

</html>