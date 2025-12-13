<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa fa-file"></i> Cetak Akta Kelahiran
        </h3>
    </div>
    <form action="./report/cetak_akta_lahir.php" method="post" target="_blank" enctype="multipart/form-data">
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Data Kelahiran</label>
                <div class="col-sm-6">
                    <select name="id_lahir" id="id_lahir" class="form-control select2bs4" required>
                        <option selected="selected">- Pilih Data Kelahiran -</option>
                        <?php
                        // Ambil data kelahiran dari database
                        $query = "
                            SELECT 
                                l.id_lahir,
                                l.nik,
                                l.nama,
                                l.tgl_lh,
                                l.jekel,
                                k.no_kk,
                                ibu.nama as nama_ibu,
                                ayah.nama as nama_ayah
                            FROM tb_lahir l
                            LEFT JOIN tb_kk k ON l.id_kk = k.id_kk
                            LEFT JOIN tb_pdd ibu 
                                ON l.id_ibu = ibu.id_pend
                                AND ibu.jekel = 'Perempuan'
                            LEFT JOIN tb_pdd ayah 
                                ON l.id_bapak = ayah.id_pend
                                AND ayah.jekel = 'Laki-laki'
                            ORDER BY l.tgl_lh DESC
                        ";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                            $tgl_lahir = date("d-m-Y", strtotime($row['tgl_lh']));
                            $nama_ayah = $row['nama_ayah'] ?: '-';
                            $nama_ibu = $row['nama_ibu'] ?: '-';
                            ?>
                            <option value="<?php echo $row['id_lahir'] ?>">
                                <?php echo $row['nama'] ?>
                                (<?php echo $tgl_lahir ?>) -
                                Ayah: <?php echo $nama_ayah ?>,
                                Ibu: <?php echo $nama_ibu ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-6">
                    <small class="text-muted">
                        <i class="fa fa-info-circle"></i>
                        Pilih data kelahiran yang akan dicetak akta kelahirannya.
                    </small>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info" name="btnCetak">
                <i class="fa fa-print"></i> Cetak Akta Kelahiran
            </button>
        </div>
    </form>
</div>