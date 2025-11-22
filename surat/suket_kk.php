<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa fa-file"></i> Surat Kartu Keluarga
        </h3>
    </div>
    <form action="./report/cetak_kk.php" method="post" enctype="multipart/form-data">
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kepala Keluarga</label>
                <div class="col-sm-6">
                    <select name="id_kk" id="id_kk" class="form-control select2bs4" required>
                        <option selected="selected">- Pilih KK -</option>
                        <?php
                        // ambil data dari database
                        $query = "SELECT k.id_kk, k.no_kk, p.nama FROM tb_kk k LEFT JOIN tb_pdd p ON k.id_kepala = p.id_pend";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                            ?>
                            <option value="<?php echo $row['id_kk'] ?>">
                                <?php echo $row['no_kk'] ?>
                                -
                                <?php echo $row['nama'] ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info" name="btnCetak" target="_blank">Cetak Surat</button>
        </div>
    </form>
</div>