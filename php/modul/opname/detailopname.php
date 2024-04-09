<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';

    //Mengecek Jabatan yang Dapat Mengakses Halaman Opname
    if($_SESSION['Jabatan']=='Penjaga Toko'){
        header('location:../../index.php');
    }
		
    $_SESSION['tambah']='false';
    $_SESSION['ubah']='false';
    $_SESSION['hapus']='false';
    $_SESSION['terima']='false';
    $_SESSION['tolak']='false';
    $_SESSION['gagal']='false';
    $_SESSION['over']='false';

    // Mengambil data idOpname
	$fetchIdOpname = $_GET['id'];
    $query=mysqli_query($conn,"SELECT *,tbopname.Status AS Stat FROM tbopname
    INNER JOIN tbpengguna ON tbopname.idPengguna=tbpengguna.idPengguna
    WHERE idOpname='$fetchIdOpname';");
	$rowBarangOpname = mysqli_fetch_array($query);

	$idOpname=$rowBarangOpname['idOpname'];
    $tanggalOpname=$rowBarangOpname['tanggalOpname'];
    $Keterangan=$rowBarangOpname['Keterangan'];
    $idPengguna=$rowBarangOpname['idPengguna'];
    $Pemeriksa=$rowBarangOpname['Nama'];
    $Status=(int) $rowBarangOpname['Stat'];

    // Proses Menyimpan Perubahan Informasi Data Barang Masuk
    if(isset($_POST['simpanDataOpname'])){
        $idBarangMasuk = $_POST['idOpname'];
        $tanggalOpname = $_POST['tanggalOpname'];
        $Keterangan=$_POST['Keterangan'];
        $idPengguna=$_POST['idPengguna'];

        $simpanDataMasuk = mysqli_query($conn,"UPDATE tbopname SET tanggalOpname ='$tanggalOpname ',Keterangan='$Keterangan',idPengguna='$idPengguna' WHERE idOpname='$idOpname'");
        
        // Kueri mengubah data detail barang masuk
        if($simpanDataMasuk){
            $_SESSION['ubah']='true'; 
        }else{
            $_SESSION['gagal']='true';   
        } 
    }

    // 
    if(isset($_POST['tambahDetailOpname'])){
        $idOpname= $_POST['idOpname'];
        $idProduk= $_POST['idProduk'];

        $queryProduk=mysqli_query($conn, "SELECT stokProduk FROM tbproduk WHERE idProduk='$idProduk'");
        $rowProduk=mysqli_fetch_array($queryProduk);
        $jumlahSistem=$rowProduk['stokProduk'];
        $jumlahOpname=$_POST['jumlahOpname'];
        $Selisih=$jumlahOpname-$jumlahSistem;

        $cekIdOpname =mysqli_query($conn, "SELECT * FROM tbdetailopname WHERE idOpname='$idOpname'");
        $noUrut=mysqli_num_rows($cekIdOpname);
        if($noUrut>0){
            $nextNoUrut = $noUrut + 1;
            $Jumlah=sprintf("%02s",$nextNoUrut);
        }else{
            $Jumlah="01";
        }
        $idDetailOpname=$idOpname."-".$Jumlah;
        $Alasan= $_POST['Alasan'];
        
        // Kueri menambah data detail barang masuk
        if($jumlahOpname<=$jumlahSistem){
            mysqli_query($conn,"INSERT INTO tbdetailopname(idDetailOpname,idOpname,idProduk,jumlahSistem,jumlahFisik,Alasan) VALUES ('$idDetailOpname','$idOpname','$idProduk','$jumlahSistem','$jumlahOpname','$Alasan')");
            $_SESSION['tambah']='true'; 
        }else{
            $_SESSION['over']='true';
        } 
    }
        
    // 
    if(isset($_POST['ubahDetailOpname'])){
        $idOpname= $_POST['idOpname'];
        $idProduk= $_POST['idProduk'];
        $idDetailOpname= $_POST['idDetailOpname'];
        
        $queryProduk=mysqli_query($conn, "SELECT stokProduk FROM tbproduk WHERE idProduk='$idProduk'");
        $rowProduk=mysqli_fetch_array($queryProduk);
        $jumlahSistem=$rowProduk['stokProduk'];
        $jumlahOpname= $_POST['jumlahOpname'];
        $Selisih=$jumlahOpname-$jumlahSistem;
        $Alasan= $_POST['Alasan'];

        if($jumlahOpname<=$jumlahSistem){
            mysqli_query($conn,"UPDATE tbdetailopname SET idProduk='$idProduk',jumlahFisik='$jumlahOpname',jumlahSistem='$jumlahSistem',Alasan='$Alasan' WHERE idDetailOpname='$idDetailOpname'");
            $_SESSION['ubah']='true'; 
        }else{
            $_SESSION['over']='true';
        } 
    }

    // 
    if(isset($_POST['hapusDetailOpname'])){
        $idOpname= $_POST['idOpname'];
        $idDetailOpname= $_POST['idDetailOpname'];
        $hapusDetailOpname = mysqli_query($conn,"DELETE FROM tbdetailopname WHERE idDetailOpname='$idDetailOpname'");
        
        // Kueri menghapus data detail barang masuk
        if($hapusDetailOpname){
            $_SESSION['hapus']='true';
        }else{
            $_SESSION['gagal']='true';
        } 
    }

    // 
    if(isset($_POST['hapusOpname'])){
        $idOpname= $_POST['idOpname'];
        $hapusDetailOpname =mysqli_query($conn,"DELETE FROM tbdetailopname WHERE idOpname='$idOpname'");
        $hapusOpname = mysqli_query($conn,"DELETE FROM tbopname WHERE idOpname='$idOpname'");
        
        // Kueri menghapus data barang masuk dan data detail barang masuk
        if($hapusOpname OR $hapusDetailOpname){
            echo '<script type="text/javascript">window.location.href = "dataopname.php";</script>';
            $_SESSION['tolak']='true';
        }else{
            $_SESSION['gagal']='true';
        } 
    }
 
    // 
    if(isset($_POST['terimaOpname'])){
        $idOpname= $_POST['idOpname'];
        $rowDetailOpname = mysqli_query($conn,"SELECT * FROM tbdetailopname 
        INNER JOIN tbproduk ON tbdetailopname.idProduk=tbproduk.idProduk
        WHERE idOpname='$idOpname'");
        $rowOpname=mysqli_query($conn,"UPDATE tbopname SET Status='1' WHERE idOpname='$idOpname'");

        while($r=mysqli_fetch_array($rowDetailOpname)){
            if($r['jumlahFisik']<=$r['stokProduk']){
                $queryUpdate=mysqli_query($conn,"UPDATE tbopname SET Status='1' WHERE idOpname='$idOpname'");
                if($queryUpdate){
                    mysqli_query($conn,"UPDATE tbproduk SET stokProduk='".$r['jumlahFisik']."' WHERE idProduk='".$r['idProduk']."'");
                    $_SESSION['terima']='true';
                }else{
                    $_SESSION['gagal']='true';
                } 
            }
            else{
                $_SESSION['over']='true';
            } 
        }
        if($_SESSION['gagal']!='true'){
            $rowProduk=mysqli_query($conn, "SELECT * FROM tbproduk 
            LEFT JOIN tbdetailopname ON tbproduk.idProduk=tbdetailopname.idProduk
            LEFT JOIN tbopname ON tbdetailopname.idOpname=tbopname.idOpname
            WHERE tbopname.idOpname='$idOpname'");
    
            while($r2=mysqli_fetch_array($rowProduk)){
                $Keterangan=$r2['Keterangan'].' ('.$r2['Alasan'].')';
                $jumlahSistem=$r2['jumlahSistem'];
                $jumlahFisik=$r2['jumlahFisik'];
                $Selisih=$jumlahSistem-$jumlahFisik;
                mysqli_query($conn,"INSERT INTO tblog (idProduk,Tanggal,Keterangan,stokOpname,totalStok) VALUES ('".$r2['idProduk']."','".$r2['tanggalOpname']."','".$Keterangan."','".$Selisih."','".$r2['stokProduk']."')");
                $_SESSION['terima']='true';
            }
        }else{
            $_SESSION['gagal']='true';
        }
    } 
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <!--Judul Halaman dan Ikon Aplikasi-->
        <title>TB. Mitra Anda</title>
        <link rel = "icon" href = "../../../assets/img/logo.png" type = "image/png">

        <!-- Kustomisasi Font Template-->
        <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!--Kustomisasi CSS Bootstrap dan Datatables-->
        <link href="../../../css/sb-admin-2.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    </head>
    <!-- Akhiran Tag Head HTML -->

    <!-- Body HTML -->
    <body id="page-top">
        <!-- Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../index.php">
                    <img class="img-fluid" src="../../../assets/img/logotext.png"></img>
                </a>

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="../../index.php">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Dashboard</span></a>
                </li>

                <div class="sidebar-heading">
                    Antarmuka
                </div>

                <!-- Nav Item - Master Data -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
                    aria-expanded="true" aria-controls="collapseMaster">
                        <i class="fas fa-fw fa-desktop"></i>
                        <span>Master Data</span>
                    </a>  
                    <div id="collapseMaster" class="collapse" aria-labelledby="headingMaster" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="../master/produk.php"><i class="fas fa-fw fa-box"></i>Produk</a>
                            <?php 
                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                if($jabatan){
                            ?>
                            <a class="collapse-item" href="../master/pemasok.php"><i class="fas fa-fw fa-truck"></i>Pemasok</a>
                            <a class="collapse-item" href="../master/satuan.php"><i class="fas fa-fw fa-weight-hanging"></i>Satuan</a>
                            <?php 
                            }?>
                        </div>
                    </div>
                </li>
                
                <!-- Nav Item - Stok -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStok"
                    aria-expanded="true" aria-controls="collapseStok">
                        <i class="fas fa-fw fa-boxes"></i>
                        <span>Transaksi</span>
                    </a>
                        
                    <div id="collapseStok" class="collapse" aria-labelledby="headingBarang" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Checker';
								if($jabatan OR $jabatan2){
							?>
							<a class="collapse-item" href="../barangmasuk/databarangmasuk.php"><i class="fas fa-fw fa-arrow-down"></i>Barang Masuk</a>
                            <?php 
								}
							?>
                            <?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Penjaga Toko';
								if($jabatan OR $jabatan2){
							?> 
                            <a class="collapse-item" href="../barangkeluar/databarangkeluar.php"><i class="fas fa-fw fa-arrow-up"></i>Barang Keluar</a> 
                            <?php 
								}
							?>
                            <?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Checker';
								if($jabatan OR $jabatan2){
							?>
							<a class="collapse-item" href="../retur/databarangretur.php"><i class="fas fa-fw fa-retweet"></i>Retur Barang</a>
                            <?php 
								}
							?>
                        </div>
                    </div>
                </li>

                <?php 
                    $jabatan=$_SESSION['Jabatan']=='Owner';
                    $jabatan2=$_SESSION['Jabatan']=='Checker';
                    if($jabatan OR $jabatan2){
                ?>
                <!-- Nav Item - Opname- -->
                <li class="nav-item">
                    <a class="nav-link" href="../opname/dataopname.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Opname Stok</span></a>
                </li>
                <?php 
                    }
                ?>

                <!-- Nav Item - Pengguna -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengguna"
                    aria-expanded="true" aria-controls="collapsePengguna">
                        <i class="fas fa-fw fa-user"></i>
                        <span>Pengguna</span>
                    </a>
                    <div id="collapsePengguna" class="collapse" aria-labelledby="headingPengguna" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?php 
                            $jabatan=$_SESSION['Jabatan']=='Owner';
                            if($jabatan){
                            ?>
                            <a class="collapse-item" href="../pengguna/pengguna.php"><i class="fas fa-fw fa-users"></i>Kelola Data Pengguna</a>
                            <?php 
                            }?> 
                            <a class="collapse-item" href="../pengguna/ubahpassword.php"><i class="fas fa-fw fa-lock"></i>Ubah Password</a>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Laporan -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                    aria-expanded="true" aria-controls="collapseLaporan">
                        <i class="fas fa-fw fa-clipboard-list"></i>
                        <span>Laporan</span>
                    </a>
                        
                    <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?php 
                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                $jabatan2=$_SESSION['Jabatan']=='Checker';
                                if($jabatan OR $jabatan2){
                            ?>
                            <a class="collapse-item" href="../laporan/laporanbarangmasuk.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Masuk</a>
                            <?php 
								}
							?>
                            <?php 
                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                $jabatan2=$_SESSION['Jabatan']=='Penjaga Toko';
                                if($jabatan OR $jabatan2){
                            ?> 
                            <a class="collapse-item" href="../laporan/laporanbarangkeluar.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Keluar</a>
                            <?php 
								}
							?>
                            <?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Checker';
								if($jabatan OR $jabatan2){
							?> 
                            <a class="collapse-item" href="../laporan/laporanbarangretur.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Retur</a>
							<a class="collapse-item" href="../laporan/laporanopnamebarang.php"><i class="fas fa-fw fa-bars"></i>Laporan Opname Barang</a>
							<?php 
								}
							?> 
                        </div>
                    </div>
                </li>
            </ul>
            <!-- Akhiran Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo 'Halo, '. $_SESSION['Nama'];?></span>
                                    <img class="img-profile rounded-circle"
                                        src="../../../assets/img/undraw_profile.svg">
                                </a>
                                <!-- Dropdown - Keluar -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Keluar
                                    </a>
                                </div>
                            </li>
                        </ul>
                        <!-- Akhiran Topbar Navbar -->
                    </nav>
                    <!-- Akhiran Topbar -->

                    <!-- Page Content -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800">Data Detail Barang Opname</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data opname berhasil ditambah pada data detail opname.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Data opname berhasil diubah pada data detail opname.
                                </div>';
                            }else if($_SESSION['hapus']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data opname berhasil dihapus pada data detail opname.
                                </div>';
                            }else if($_SESSION['terima']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data opname berhasil diterima.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-secondary" role="alert">
                                    Data opname tidak terkoneksi.
                                </div>';
                            }
                        ?>      
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">ID Opname:</label>
                                    <input type="text" class="form-control" id="idOpname" name="idOpname" value="<?php echo $idOpname;?>" readonly>
                                </div>
                                <?php if($Status==0){?>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Tanggal Opname:</label>
                                        <input type="date" class="form-control" id="tanggalOpname" name="tanggalOpname" value="<?php echo $tanggalOpname;?>" max="<?= date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Keterangan:</label>
                                        <input type="text" class="form-control" value="<?php echo $Keterangan;?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Pemeriksa:</label>
                                        <select class="form-control" id="exampleFormControlSelect1" id="idPengguna" name="idPengguna">
                                        <?php
                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbpengguna
                                            WHERE idPengguna='$idPengguna'");
                                            $dataSelect=mysqli_fetch_array($querySelect);
                                        ?>
                                        <option value="<?php echo $dataSelect['idPengguna'];?>" hidden><?php echo $dataSelect['Nama'];?></option>
                                        <?php
                                            $query    =mysqli_query($conn, "SELECT * FROM tbpengguna ORDER BY idPengguna");
                                            while ($data = mysqli_fetch_array($query)) {
                                            ?>
                                            <option value="<?=$data['idPengguna'];?>"><?php echo $data['Nama'];?></option>
                                            <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                <?php } else{?>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Tanggal Opname:</label>
                                        <input type="date" class="form-control" id="tanggalOpname" name="tanggalOpname" value="<?php echo $tanggalOpname;?>" max="<?= date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Keterangan:</label>
                                        <input type="text" class="form-control" value="<?php echo $Keterangan;?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Pemeriksa:</label>
                                        <select class="form-control" id="exampleFormControlSelect1" id="idPengguna" name="idPengguna" readonly>
                                        <?php
                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbpengguna
                                            WHERE idPengguna='$idPengguna'");
                                            $dataSelect=mysqli_fetch_array($querySelect);
                                        ?>
                                        <option value="<?php echo $dataSelect['idPengguna'];?>" hidden><?php echo $dataSelect['Nama'];?></option>
                                        <?php
                                            $query    =mysqli_query($conn, "SELECT * FROM tbpengguna ORDER BY idPengguna");
                                            while ($data = mysqli_fetch_array($query)) {
                                            ?>
                                            <option value="<?=$data['idPengguna'];?>"><?php echo $data['Nama'];?></option>
                                            <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                <?php }?>
                                <?php if($Status==0){?>
                                    <button type="submit" class="btn btn-success" name="simpanDataKeluar">
                                        <i class="fas fa-save">Simpan</i>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">
                                        <i class="fas fa-plus">Tambah</i>
                                    </button>
                                <?php }?>
                            </div>
                            <div class="card-body">
                                <!-- Modal Tambah -->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Detail Barang Opname</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Produk & Jumlah Sistem:</label>
                                                        <select class="form-control selectpicker" title="Pilih Produk"  data-live-search="true" id="exampleFormControlSelect1" id="idProduk" name="idProduk">
                                                            <?php
                                                            // INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan  
                                                                $query =mysqli_query($conn, "SELECT * FROM tbproduk INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan WHERE tbproduk.idProduk NOT IN (SELECT DISTINCT tbdetailopname.idProduk FROM tbdetailopname WHERE tbdetailopname.idOpname = '$fetchIdOpname') ORDER BY tbproduk.Produk ");         
                                                                // $query =mysqli_query($conn, "SELECT * FROM tbproduk WHERE tbproduk.idProduk NOT IN (SELECT DISTINCT tbdetailopname.idProduk FROM tbdetailopname WHERE tbdetailopname.idBarangOpname = '$fetchIdOpname') ORDER BY tbproduk.Produk");
                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                                                <option value="<?=$data['idProduk'];?>"><?php echo $data['Produk'].' - '.$data['stokProduk'].' '.$data['Satuan'].'';?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="col-form-label">Jumlah Opname:</label>
                                                        <input type="number" class="form-control" id="jumlahOpname" name="jumlahOpname" min="0" placeholder="Masukkan Jumlah Opname" oninput="validity.valid||(value='');" required>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="col-form-label">Alasan:</label>
                                                        <input type="text" class="form-control" id="Alasan" name="Alasan" placeholder="Masukkan Alasan" required>
                                                    </div> 
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idOpname" value="<?=$idOpname;?>">
                                                        <input type="hidden" name="idDetailOpname" value="<?=$idDetailOpname;?>">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary" name="tambahDetailOpname">Tambah</button>
                                                    </div>
                                                </div>
                                            </form>
											<script>
												$('#tambah').on('hidden.bs.modal', function () {
													$(this).find('form').trigger('reset');
												})
											</script>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datatables -->
                                <div id="datatables">
                                    <table class="table table-stripped table-bordered table-hover" id="dtDetailOpname" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr  class="text-center">
                                                <th>No.</th>
                                                <th>Produk</th>
                                                <th>Jumlah Sistem</th>
                                                <th>Jumlah Opname</th>
                                                <th>Pengurangan Stok</th>
                                                <th>Alasan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDetailOpname= mysqli_query($conn,"
                                                SELECT *,tbopname.Status AS Stat FROM tbdetailopname
                                                INNER JOIN tbopname ON tbdetailopname.idOpname=tbopname.idOpname
                                                INNER JOIN tbproduk ON tbdetailopname.idProduk=tbproduk.idProduk
                                                INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                WHERE tbdetailopname.idOpname='$fetchIdOpname'");
                                                $inc=1;
                                                while($data=mysqli_fetch_array($tampilDetailOpname)){
                                                    $idDetailOpname = $data['idDetailOpname'];
                                                    $idOpname = $data['idOpname'];
                                                    $idProduk =$data['idProduk'];
                                                    $Produk =$data['Produk'];
                                                    $Satuan =$data['Satuan'];
                                                    $jumlahOpname =$data['jumlahFisik'];
                                                    $jumlahSistem =$data['jumlahSistem'];
                                                    $Selisih=-($jumlahOpname-$jumlahSistem);
                                                    $Alasan =$data['Alasan'];
                                                    $Status=(int) $data['Stat'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$inc++;?></td>
                                                <td><?=$Produk;?></td>
                                                <td class="text-center"><?=$jumlahSistem.' '.$Satuan;?></td>
                                                <td class="text-center"><?=$jumlahOpname.' '.$Satuan;?></td>
                                                <td class="text-center"><?=$Selisih;?></td>
                                                <td><?=$Alasan;?></td>
                                                <td class="text-center">
                                                    <?php if($Status == 1){?>
                                                        <i class="fas fa-check">Disetujui</i>
                                                    <?php }else{?>
                                                        <button type="button" class="btn btn-warning btn-sm mb-4" data-toggle="modal" data-target="#ubah<?=$idDetailOpname;?>">
                                                            <i class="fas fa-edit">Ubah</i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm mb-4" data-toggle="modal" data-target="#hapus<?=$idDetailOpname;?>">
                                                            <i class="fas fa-trash">Hapus</i> 
                                                        </button>
                                                    <?php }?>
                                                </td>
                                            </tr>

                                            <!-- Modal Ubah -->
                                            <div class="modal fade" id="ubah<?=$idDetailOpname;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Ubah Detail Opname</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="message-text" class="col-form-label">Produk & Jumlah Sistem:</label>
                                                                    <select class="form-control" id="idProduk" name="idProduk">
                                                                        <?php
                                                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbproduk INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan WHERE idProduk='$idProduk' ORDER BY Produk");
                                                                            $dataSelect=mysqli_fetch_array($querySelect);
                                                                        ?>
                                                                        <option value="<?php echo $idProduk;?>" hidden><?php echo $dataSelect['Produk'].' - '.$dataSelect['stokProduk'].' '.$dataSelect['Satuan'].'';?></option>
                                                                        <?php
                                                                            $query    =mysqli_query($conn, "SELECT * FROM tbproduk INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan ORDER BY Produk");
                                                                            while ($data = mysqli_fetch_array($query)) {
                                                                            ?>
                                                                            <option value="<?=$data['idProduk'];?>"><?php echo $data['Produk'].' - '.$data['stokProduk'].' '.$data['Satuan'].'';?></option>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Jumlah Opname:</label>
                                                                    <input type="number" class="form-control" id="jumlahOpname" name="jumlahOpname" min="0" value="<?php echo $jumlahOpname;?>" oninput="validity.valid||(value='');">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="recipient-name" class="col-form-label">Alasan:</label>
                                                                    <input type="text" class="form-control" id="Alasan" name="Alasan" placeholder="Masukkan Alasan" value="<?php echo $Alasan;?>">
                                                                </div> 
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idOpname" value="<?=$idOpname;?>">
                                                                <input type="hidden" name="idDetailOpname" value="<?=$idDetailOpname;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning" name="ubahDetailOpname">Ubah</button>
                                                            </div>
                                                        </form>
														<script>
															$('#ubah<?=$idDetailOpname;?>').on('hidden.bs.modal', function () {
																$(this).find('form').trigger('reset');
															})
														</script>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="hapus<?=$idDetailOpname;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin ingin menghapus produk ini?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <b><?php echo 'Nama Produk: '.$Produk;?></b>
                                                                    <br><?php echo 'Jumlah Sistem: '.$jumlahSistem.' '.$Satuan;?>
                                                                    <br><?php echo 'Jumlah Opname: '.$jumlahOpname.' '.$Satuan;?>
                                                                    <br><?php echo 'Alasan: '.$Alasan;?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idOpname" value="<?=$idOpname;?>">
                                                                    <input type="hidden" name="idDetailOpname" value="<?=$idDetailOpname;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="hapusDetailOpname">Hapus</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
                                            <?php
                                                };
                                            ?>

                                            <!-- Modal Tolak -->
                                            <div class="modal fade" id="tolak<?=$idOpname;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin untuk menolak permintaan opname tersebut?</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idOpname" value="<?=$idOpname;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="hapusOpname">Tolak</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>

                                            <!-- Modal Terima -->
                                            <div class="modal fade" id="terima<?=$idOpname;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin untuk menerima permintaan opname tersebut?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idOpname" value="<?=$idOpname;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success" name="terimaOpname">Terima</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
                                        </tbody>
                                    </table>
                                    <?php if($Status==1){?>
                                    <?php } else{?>
                                            <?php 
                                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                                if($jabatan){
                                            ?>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#terima<?=$idOpname;?>"><i class="fas fa-check">Terima</i></button>
                                                <button class="btn btn-danger ml-2" type="button" data-toggle="modal" data-target="#tolak<?=$idOpname;?>"><i class="fas fa-times">Tolak</i></button>
                                            </div>
                                            <?php 
                                                }
                                            ?>
                                        <?php }?>
                                    </div>
                                </div>   
                            </div>
                        </div>
                </div>
                <!-- Akhiran Page Content -->
                <script>
                    if ( window.history.replaceState ) {
                        window.history.replaceState( null, null, window.location.href );
                    }
                </script>
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; TB. Mitra Anda 2023</span>
                        </div>
                    </div>
                </footer>
                <!-- Akhiran Footer -->
            </div>
            <!-- Akhiran Content Wrapper -->
        </div>
        <!-- Akhiran Wrapper -->

        <!-- Scroll ke Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Modal Logout-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin untuk keluar dari akun anda?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Pilih "Keluar" dibawah ini untuk mengakhiri sesi anda.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <a class="btn btn-danger" href="../../logout.php">Keluar</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skrip Datatables -->
        <script>
        $(document).ready(function() {
            $('#dtDetilMasuk').DataTable( {
                dom: 'Brfltip',
                lengthMenu: [25, 50, 100, 200, 500],
                scrollX:true
            } );
        } );
        </script>

        <!-- JavaScript Bootstrap dan Datatables-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.3.3/js/dataTables.rowReorder.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="../../../js/sb-admin-2.min.js"></script>
    </body>
    <!-- Akhiran Tag Body HTML -->
</html>