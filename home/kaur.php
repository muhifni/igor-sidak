<?php

  $sql = $koneksi->query("SELECT COUNT(id_pend) as pend  from tb_pdd where status='Ada'");
  while ($data= $sql->fetch_assoc()) {
    $pend=$data['pend'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_kk) as kartu  from tb_kk");
  while ($data= $sql->fetch_assoc()) {
    $kartu=$data['kartu'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_pend) as laki  from tb_pdd where jekel='LK'");
  while ($data= $sql->fetch_assoc()) {
    $laki=$data['laki'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_pend) as prem  from tb_pdd where jekel='PR'");
  while ($data= $sql->fetch_assoc()) {
    $prem=$data['prem'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_lahir) as lahir from tb_lahir");
  while ($data= $sql->fetch_assoc()) {
    $lahir=$data['lahir'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_mendu) as mendu  from tb_mendu");
  while ($data= $sql->fetch_assoc()) {
    $mendu=$data['mendu'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_datang) as datang  from tb_datang");
  while ($data= $sql->fetch_assoc()) {
    $datang=$data['datang'];
  }

  $sql = $koneksi->query("SELECT COUNT(id_pindah) as pindah  from tb_pindah");
  while ($data= $sql->fetch_assoc()) {
    $pindah=$data['pindah'];
  }

?>

<div class="row">
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-info">
			<div class="inner">
				<h3>
					<?php echo $pend;  ?>
				</h3>

				<p>Penduduk</p>
			</div>
			<div class="icon">
				<i class="fas fa-users"></i>
			</div>
			<a href="index.php?page=data-pend" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-success">
			<div class="inner">
				<h3>
					<?php echo $kartu;  ?>
				</h3>

				<p>Kartu Keluarga</p>
			</div>
			<div class="icon">
				<i class="fas fa-id-card"></i>
			</div>
			<a href="index.php?page=data-mendu" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>
					<?php echo $laki;  ?>
				</h3>

				<p>Laki-laki</p>
			</div>
			<div class="icon">
				<i class="fas fa-male"></i>
			</div>
			<a href="index.php?page=data-pend" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-warning">
			<div class="inner">
				<h3>
					<?php echo $prem;  ?>
				</h3>

				<p>Perempuan</p>
			</div>
			<div class="icon">
				<i class="fas fa-female"></i>
			</div>
			<a href="index.php?page=data-pend" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-info">
			<div class="inner">
				<h3>
					<?php echo $lahir;  ?>
				</h3>

				<p>Lahir</p>
			</div>
			<div class="icon">
				<i class="fas fa-baby"></i>
			</div>
			<a href="index.php?page=data-pend" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-success">
			<div class="inner">
				<h3>
					<?php echo $mendu;  ?>
				</h3>

				<p>Meninggal</p>
			</div>
			<div class="icon">
				<i class="fas fa-pray"></i>
			</div>
			<a href="index.php?page=data-mendu" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>
					<?php echo $datang;  ?>
				</h3>

				<p>Pendatang</p>
			</div>
			<div class="icon">
				<i class="fas fa-user-plus"></i>
			</div>
			<a href="index.php?page=data-datang" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-6">
		<!-- small box -->
		<div class="small-box bg-warning">
			<div class="inner">
				<h3>
					<?php echo $pindah;  ?>
				</h3>

				<p>Pindah</p>
			</div>
			<div class="icon">
				<i class="fas fa-user-minus"></i>
			</div>
			<a href="index.php?page=data-pindah" class="small-box-footer">Selengkapnya
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>

</div>