<?php
session_start();
include "inc/koneksi.php";
include "config_payment.php";

$access_status = checkAccessStatus();
$notification_message = getNotificationMessage();
$days_remaining = getDaysRemaining();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Akses Terbatas | SIDAK</title>
    <link rel="icon" href="dist/img/logo_Sumba_Barat_Daya.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition lockscreen">
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <img src="dist/img/logo_Sumba_Barat_Daya.jpg" width="100px" alt="Logo">
        <br><br>
        <b>Sistem Data Kependudukan</b><br>
        <small>Desa Karuni</small>
    </div>
    
    <div class="lockscreen-name">
        <?php 
        if ($access_status == 'blocked') {
            echo '<i class="fas fa-lock text-danger"></i> Akses Diblokir';
        } elseif ($access_status == 'expired') {
            echo '<i class="fas fa-calendar-times text-danger"></i> Aplikasi Expired';
        } else {
            echo '<i class="fas fa-exclamation-triangle text-warning"></i> Peringatan';
        }
        ?>
    </div>
    
    <div class="lockscreen-item">
        <div class="card">
            <div class="card-body text-center">
                <?php if ($access_status == 'blocked'): ?>
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Akses Ditolak!</h5>
                        <?php echo $notification_message; ?>
                    </div>
                    <p class="text-muted">
                        Hubungi administrator untuk mengaktifkan kembali akses aplikasi.
                    </p>
                    
                <?php elseif ($access_status == 'expired'): ?>
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-calendar-times"></i> Aplikasi Expired!</h5>
                        <?php echo $notification_message; ?>
                    </div>
                    <p class="text-muted">
                        Silakan perpanjang subscription untuk melanjutkan penggunaan aplikasi.
                    </p>
                    
                <?php else: ?>
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                        <?php echo $notification_message; ?>
                    </div>
                    <p class="text-muted">
                        Segera perpanjang subscription sebelum aplikasi expired.
                    </p>
                    
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Aplikasi
                    </a>
                <?php endif; ?>
                
                <hr>
                
                <div class="row">
                    <div class="col-6">
                        <a href="login.php" class="btn btn-secondary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="logout.php" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="help-block text-center">
        <small class="text-muted">
            Sistem Data Kependudukan Desa Karuni<br>
            &copy; <?php echo date('Y'); ?> - Semua hak dilindungi
        </small>
    </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>