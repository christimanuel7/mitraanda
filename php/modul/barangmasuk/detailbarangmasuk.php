<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';
	
    $_SESSION['simpan']='false';
    $_SESSION['tambah']='false';
    $_SESSION['ubah']='false';
    $_SESSION['hapus']='false';
    $_SESSION['terima']='false';
    $_SESSION['tolak']='false';
    $_SESSION['gagal']='false';

    if ($_SESSION['Jabatan'] == 'Penjaga Toko') {
        header('location:../../index.php');
    }

    // Mengambil data idBarangMasuk
	$fetchIdBarangMasuk = $_GET['id'];
    $query=mysqli_query($conn, "SELECT *,tbstokmasuk.Status AS Stat FROM tbstokmasuk 
    INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
    INNER JOIN tbpengguna ON tbstokmasuk.idPengguna=tbpengguna.idPengguna 
    WHERE idBarangMasuk='$fetchIdBarangMasuk'");
    $rowBarangMasuk =mysqli_fetch_array($query);

	$idBarangMasuk = $rowBarangMasuk['idBarangMasuk'];
	$tanggalMasuk = $rowBarangMasuk['tanggalMasuk'];
    $Pemasok=$rowBarangMasuk['Pemasok'];
    $idPengguna=$rowBarangMasuk['idPengguna'];
    $idPemasok=$rowBarangMasuk['idPemasok'];
	$Penerima=$rowBarangMasuk['Nama'];
	$Status = (int) $rowBarangMasuk['Stat'];
        
    // Proses Menyimpan Perubahan Informasi Data Barang Masuk
    if(isset($_POST['simpanDataMasuk'])){
        $idBarangMasuk = $_POST['idBarangMasuk'];
        $tanggalMasuk = $_POST['tanggalMasuk'];
        $idPengguna=$_POST['idPengguna'];
        $idPemasok=$_POST['idPemasok'];

        $simpanDataMasuk = mysqli_query($conn,"UPDATE tbstokmasuk SET tanggalMasuk='$tanggalMasuk',idPengguna='$idPengguna',idPemasok='$idPemasok' WHERE idBarangMasuk='$idBarangMasuk'");
        
        // Kueri mengubah data detail barang masuk
        if($simpanDataMasuk){
            $_SESSION['simpan']='true'; 
        }else{
            $_SESSION['gagal']='true';   
        } 
    }

    // Proses Menambah Detail Produk Barang Masuk, Ketika Data Detail Barang Masuk Ditambah
    if(isset($_POST['tambahDetailProdukMasuk'])){
        $idBarangMasuk= $_POST['idBarangMasuk'];
        $idProduk= $_POST['idProduk'];
        $hargaMasuk= $_POST['hargaMasuk'];
        $jumlahMasuk= $_POST['jumlahMasuk'];
        $cekIDMasuk =mysqli_query($conn, "SELECT * FROM tbdetailmasuk WHERE idBarangMasuk='$idBarangMasuk'");
        $noUrut=mysqli_num_rows($cekIDMasuk);
        if($noUrut>0){
            $nextNoUrut = $noUrut + 1;
            $Jumlah=sprintf("%02s",$nextNoUrut);
        }else{
            $Jumlah="01";
        }
        $idDetailMasuk=$idBarangMasuk."-".$Jumlah;
        $tambahDetailProdukMasuk = mysqli_query($conn,"INSERT INTO tbdetailmasuk(idDetailMasuk,idBarangMasuk,idProduk,hargaMasuk,jumlahMasuk) VALUES ('$idDetailMasuk','$idBarangMasuk','$idProduk','$hargaMasuk','$jumlahMasuk')");
        
        // Kueri menambah data detail barang masuk
        if($tambahDetailProdukMasuk){
            $_SESSION['tambah']='true'; 
        }else{
            $_SESSION['gagal']='true'; 
        } 
    }

    // Proses Mengubah Detail Produk Barang Masuk, Ketika Data Detail Barang Masuk Ditambah
    if(isset($_POST['ubahDetailMasuk'])){
        $idBarangMasuk= $_POST['idBarangMasuk'];
        $idDetailMasuk= $_POST['idDetailMasuk'];
        $idProduk= $_POST['idProduk'];
        $hargaMasuk= $_POST['hargaMasuk'];
        $jumlahMasuk= $_POST['jumlahMasuk'];

        $ubahDetailMasuk = mysqli_query($conn,"UPDATE tbdetailmasuk SET idProduk='$idProduk',hargaMasuk='$hargaMasuk',jumlahMasuk='$jumlahMasuk' WHERE idDetailMasuk='$idDetailMasuk'");
        
        // Kueri mengubah data detail barang masuk
        if($ubahDetailMasuk){
            $_SESSION['ubah']='true'; 
        }else{
            $_SESSION['gagal']='true';   
        } 
    }

    // Proses Menghapus Detail Produk Barang Masuk, Ketika Data Detail Barang Masuk Dihapus
    if(isset($_POST['hapusDetailProdukMasuk'])){
        $idBarangMasuk= $_POST['idBarangMasuk'];
        $idDetailMasuk= $_POST['idDetailMasuk'];
        $hapusDetailProdukMasuk = mysqli_query($conn,"DELETE FROM tbdetailmasuk WHERE idDetailMasuk='$idDetailMasuk'");
        
        // Kueri menghapus data detail barang masuk
        if($hapusDetailProdukMasuk){
            $_SESSION['hapus']='true';
        }else{
            $_SESSION['gagal']='true'; 
        } 
    }
    
    // Proses Menghapus Data Barang Masuk, Ketika Data Barang Masuk Ditolak
    if(isset($_POST['hapusBarangMasuk'])){
        $idBarangMasuk= $_POST['idBarangMasuk'];
        $hapusDetilMasuk =mysqli_query($conn,"DELETE FROM tbdetailmasuk WHERE idBarangMasuk='$idBarangMasuk'");
        $hapusProdukMasuk = mysqli_query($conn,"DELETE FROM tbstokmasuk WHERE idBarangMasuk='$idBarangMasuk'");
        
        // Kueri menghapus data barang masuk dan data detail barang masuk
        if($hapusProdukMasuk OR $hapusDetilMasuk){
            echo '<script type="text/javascript">window.location.href = "databarangmasuk.php";</script>';
            $_SESSION['tolak']='true';
        }else{
            $_SESSION['gagal']='true'; 
        } 
    }
    
    // Proses Menambah Stok Data Produk dan Mengubah Status Data Barang Keluar, Ketika Data Barang Masuk Diterima
    if(isset($_POST['terimaBarangMasuk'])){
        $idBarangMasuk= $_POST['idBarangMasuk'];
        $queryDetailMasuk = mysqli_query($conn,"SELECT * FROM tbdetailmasuk WHERE idBarangMasuk='$idBarangMasuk'");
        $queryStokMasuk=mysqli_query($conn,"UPDATE tbstokmasuk SET Status='1' WHERE idBarangMasuk='$idBarangMasuk'");
        while($r=mysqli_fetch_row($queryDetailMasuk)){
            // Kueri mengubah data detail barang masuk dan data barang masuk
            if($queryStokMasuk){
                mysqli_query($conn,"UPDATE tbproduk SET stokProduk=stokProduk+'$r[4]' WHERE idProduk='$r[2]'");
            }else{
                $_SESSION['gagal']='true'; 
            } 
        }
        $queryProduk=mysqli_query($conn, "SELECT * FROM tbproduk 
                LEFT JOIN tbdetailmasuk ON tbproduk.idProduk=tbdetailmasuk.idProduk
                LEFT JOIN tbstokmasuk ON tbdetailmasuk.idBarangMasuk=tbstokmasuk.idBarangMasuk 
                INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
                WHERE tbstokmasuk.idBarangMasuk='$idBarangMasuk'");
        while($rowProduk=mysqli_fetch_array($queryProduk)){
            $keteranganMasuk="Barang Masuk (".$rowProduk['Pemasok'].")";
            mysqli_query($conn,"INSERT INTO tblog (idProduk,Tanggal,Keterangan,stokMasuk,totalStok) VALUES ('".$rowProduk['idProduk']."','".$rowProduk['tanggalMasuk']."','".$keteranganMasuk."','".$rowProduk['jumlahMasuk']."','".$rowProduk['stokProduk']."')");
            $_SESSION['terima']='true';
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
							<a class="collapse-item" href="databarangmasuk.php"><i class="fas fa-fw fa-arrow-down"></i>Barang Masuk</a>
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

                <!-- Nav Item - Opname- -->
				<?php 
                    $jabatan=$_SESSION['Jabatan']=='Owner';
                    $jabatan2=$_SESSION['Jabatan']=='Checker';
                    if($jabatan OR $jabatan2){
                ?> 
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
							}?>
                            <?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Penjaga Toko';
								if($jabatan OR $jabatan2){
							?>
                            <a class="collapse-item" href="../laporan/laporanbarangkeluar.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Keluar</a>
                            <?php 
							}?>
                            <?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Checker';
								if($jabatan OR $jabatan2){
							?> 
                            <a class="collapse-item" href="../laporan/laporanbarangretur.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Retur</a>
							<a class="collapse-item" href="../laporan/laporanopnamebarang.php"><i class="fas fa-fw fa-bars"></i>Laporan Opname Barang</a>
							<?php 
							}?> 
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
                        <h1 class="h3 mb-2 text-gray-800">Data Detail Barang Masuk</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Produk berhasil ditambah pada data detail barang masuk.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Produk berhasil diubah pada data detail barang masuk.
                                </div>';
                            }else if($_SESSION['simpan']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Data barang masuk berhasil diubah.
                                </div>';
                            }else if($_SESSION['hapus']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Produk berhasil dihapus pada data detail barang masuk.
                                </div>';
                            }else if($_SESSION['terima']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data barang masuk berhasil diterima.
                                </div>';
                            }else if($_SESSION['tolak']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data barang masuk ditolak.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-secondary" role="alert">
                                    Data barang tidak terkoneksi.
                                </div>';
                            }
                        ?>      
                        <div class="card shadow mb-4">
							<div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">ID Barang Masuk:</label>
                                        <input type="text" class="form-control" id="idBarangMasuk" name="idBarangMasuk" value="<?php echo $idBarangMasuk;?>" readonly>
                                    </div>
                                    <?php if($Status == 0){?>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Tanggal Masuk:</label>
                                        <input type="date" class="form-control" id="tanggalMasuk" name="tanggalMasuk" value="<?php echo $tanggalMasuk;?>" max="<?= date('Y-m-d'); ?>" oninvalid="this.setCustomValidity('Pilih tanggal pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Pemasok:</label>
                                        <select class="form-control" id="exampleFormControlSelect1" id="idPemasok" name="idPemasok">
                                        <?php
                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbpemasok
                                            WHERE idPemasok='$idPemasok'");
                                            $dataSelect=mysqli_fetch_array($querySelect);
                                        ?>
                                        <option value="<?php echo $dataSelect['idPemasok'];?>" hidden><?php echo $dataSelect['Pemasok'];?></option>
                                        <?php
                                            $query    =mysqli_query($conn, "SELECT * FROM tbpemasok ORDER BY idPemasok");
                                            while ($data = mysqli_fetch_array($query)) {
                                            ?>
                                            <option value="<?=$data['idPemasok'];?>"><?php echo $data['Pemasok'];?></option>
                                            <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Penerima:</label>
                                        <select class="form-control" id="exampleFormControlSelect1" id="idPengguna" name="idPengguna">
                                        <?php
                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbpengguna WHERE idPengguna='$idPengguna'");
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
                                    <?php }else{?>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Tanggal Masuk:</label>
                                        <input type="date" class="form-control" id="tanggalMasuk" name="tanggalMasuk" value="<?php echo $tanggalMasuk;?>" max="<?= date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Pemasok:</label>
                                        <select class="form-control" id="exampleFormControlSelect1" id="idPemasok" name="idPemasok" readonly>
                                        <?php
                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbpemasok
                                            WHERE idPemasok='$idPemasok'");
                                            $dataSelect=mysqli_fetch_array($querySelect);
                                        ?>
                                        <option value="<?php echo $dataSelect['idPemasok'];?>" hidden><?php echo $dataSelect['Pemasok'];?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Penerima:</label>
                                        <select class="form-control" id="exampleFormControlSelect1" id="idPengguna" name="idPengguna" readonly>
                                        <?php
                                            $querySelect=mysqli_query($conn, "SELECT * FROM tbpengguna WHERE idPengguna='$idPengguna'");
                                            $dataSelect=mysqli_fetch_array($querySelect);
                                        ?>
                                            <option value="<?php echo $dataSelect['idPengguna'];?>" hidden><?php echo $dataSelect['Nama'];?></option>
                                        </select>
                                    </div>
                                    <?php }?>
                                    <?php if($Status == 0){?>
                                        <button type="submit" class="btn btn-success" name="simpanDataMasuk">
                                            <i class="fa fa-save">Simpan</i>
                                        </button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">
                                            <i class="fa fa-plus">Tambah</i>
                                        </button>
                                    <?php }?>
                                </form>
                            </div>
                            <div class="card-body">
                                <!-- Modal Tambah -->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Detail Barang Masuk</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Produk:</label>
                                                        <select class="form-control selectpicker" title="Pilih Produk"  data-live-search="true" id="exampleFormControlSelect1" id="idProduk" name="idProduk" oninvalid="this.setCustomValidity('Pilih opsi pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                            <?php
                                                                $query    =mysqli_query($conn, "SELECT * FROM tbproduk INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan WHERE tbproduk.idProduk NOT IN (SELECT DISTINCT tbdetailmasuk.idProduk FROM tbdetailmasuk WHERE tbdetailmasuk.idBarangMasuk = '$fetchIdBarangMasuk') ORDER BY tbproduk.Produk");                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                                                <option value="<?=$data['idProduk'];?>"><?php echo $data['Produk'].' - Stok: '.$data['stokProduk'].' '.$data['Satuan']?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="col-form-label">Harga Produk Masuk:</label>
                                                        <input type="number" class="form-control" id="hargaMasuk" name="hargaMasuk" min="0" placeholder="Masukkan Harga Produk Masuk" oninput="validity.valid||(value='');" oninvalid="this.setCustomValidity('Masukkan angka pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div> 
                                                     <div class="form-group">
                                                        <label for="recipient-name" class="col-form-label">Jumlah Masuk:</label>
                                                        <input type="number" class="form-control" id="jumlahMasuk" name="jumlahMasuk" min="1" placeholder="Masukkan Jumlah Masuk" oninput="validity.valid||(value='');" oninvalid="this.setCustomValidity('Masukkan angka pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div> 
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idBarangMasuk" value="<?=$idBarangMasuk;?>">
                                                        <input type="hidden" name="idDetailMasuk" value="<?=$idDetailMasuk;?>">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary" name="tambahDetailProdukMasuk">Tambah</button>
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
                                    <table class="table table-stripped table-bordered table-hover" id="dtDetilMasuk" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Nama Produk</th>
                                                <th class="text-center">Harga Item</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDetilProdukMasuk= mysqli_query($conn,"
                                                SELECT *,tbstokmasuk.Status AS Stat FROM tbdetailmasuk
                                                INNER JOIN tbstokmasuk ON tbdetailmasuk.idBarangMasuk=tbstokmasuk.idBarangMasuk
                                                INNER JOIN tbproduk ON tbdetailmasuk.idProduk=tbproduk.idProduk
                                                INNER JOIN tbsatuan ON tbsatuan.idSatuan=tbproduk.idSatuan
                                                WHERE tbdetailmasuk.idBarangMasuk='$fetchIdBarangMasuk';");
                                                $inc=1;
                                                while($data=mysqli_fetch_array($tampilDetilProdukMasuk)){
                                                    $idDetailMasuk = $data['idDetailMasuk'];
                                                    $idBarangMasuk = $data['idBarangMasuk'];
                                                    $idProduk =$data['idProduk'];
                                                    $Produk =$data['Produk'];
                                                    $Satuan=$data['Satuan'];
                                                    $hargaMasuk=$data['hargaMasuk'];
                                                    $konversiHargaMasuk = "Rp " . number_format($hargaMasuk,2,',','.');
                                                    $jumlahMasuk =$data['jumlahMasuk'];
                                                    $totalHargaMasuk=$hargaMasuk*$jumlahMasuk;
                                                    $konversiTotalHargaMasuk = "Rp " . number_format($totalHargaMasuk,2,',','.');
                                                    $Status=$data['Stat'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$inc++;?></td>
                                                <td><?=$Produk;?></td>
                                                <td class="text-center"><?=$konversiHargaMasuk;?></td>
                                                <td class="text-center"><?=$jumlahMasuk.' '.$Satuan;?></td>
                                                <td class="text-center"><?=$konversiTotalHargaMasuk;?></td>
                                                <td class="text-center">
                                                    <?php if($Status == 1){?>
                                                        <i class="fas fa-check">Disetujui</i>
                                                    <?php }else{?>
                                                        <button type="button" class="btn btn-warning btn-sm mb-4" data-toggle="modal" data-target="#ubah<?=$idDetailMasuk;?>">
                                                            <i class="fas fa-edit">Ubah</i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm mb-4" data-toggle="modal" data-target="#hapus<?=$idDetailMasuk;?>">
                                                            <i class="fas fa-trash">Hapus</i> 
                                                        </button>
                                                    <?php }?>
                                                </td>
                                            </tr>

                                            <!-- Modal Ubah -->
                                            <div class="modal fade" id="ubah<?=$idDetailMasuk;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Ubah Detail Barang Masuk</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="message-text" class="col-form-label">Produk:</label>
                                                                    <select class="form-control" id="exampleFormControlSelect1" id="idProduk" name="idProduk">
                                                                            <option value="<?php echo $idProduk;?>" hidden><?php echo $Produk.' - Stok: '.$data['stokProduk'].' '.$data['Satuan'];?></option>
                                                                        <?php
                                                                            $query    =mysqli_query($conn, "SELECT * FROM tbproduk INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan WHERE tbproduk.idProduk NOT IN (SELECT DISTINCT tbdetailmasuk.idProduk FROM tbdetailmasuk WHERE tbdetailmasuk.idBarangMasuk = '$fetchIdBarangMasuk') ORDER BY tbproduk.Produk");
                                                                            while ($data = mysqli_fetch_array($query)) {
                                                                            ?>
                                                                            <option value="<?=$data['idProduk'];?>"><?php echo $data['Produk'].' - Stok: '.$data['stokProduk'].' '.$data['Satuan'];?></option>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Harga Produk Masuk:</label>
                                                                    <input type="number" class="form-control" id="hargaMasuk" name="hargaMasuk" min="0" value="<?php echo $hargaMasuk;?>" oninput="validity.valid||(value='');" oninvalid="this.setCustomValidity('Masukkan angka pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Jumlah Masuk:</label>
                                                                    <input type="number" class="form-control" id="jumlahMasuk" name="jumlahMasuk" min="1" value="<?php echo $jumlahMasuk;?>" oninput="validity.valid||(value='');" oninvalid="this.setCustomValidity('Masukkan angka pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idBarangMasuk" value="<?=$idBarangMasuk;?>">
                                                                <input type="hidden" name="idDetailMasuk" value="<?=$idDetailMasuk;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning" name="ubahDetailMasuk">Ubah</button>
                                                            </div>
                                                        </form>
														<script>
															$('#ubah<?=$idDetailMasuk;?>').on('hidden.bs.modal', function () {
																$(this).find('form').trigger('reset');
															})
														</script>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="hapus<?=$idDetailMasuk;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                                    <?php echo $Produk.' ('.$jumlahMasuk.' '.$Satuan.') <br>';?>
                                                                    <?php echo 'Harga Beli per Item: '.$konversiHargaMasuk.'<br>';?>
                                                                    <?php echo 'Total Harga: '.$konversiTotalHargaMasuk;?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idBarangMasuk" value="<?=$idBarangMasuk;?>">
                                                                    <input type="hidden" name="idDetailMasuk" value="<?=$idDetailMasuk;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="hapusDetailProdukMasuk">Hapus</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
                                            <?php
                                                };
                                            ?>
                                            <!-- <tr>
                                                <td class="text-center" colspan="4">Total: </td>
                                                <td class="text-center" colspan="2">Total: </td>
                                            </tr> -->

                                            <!-- Modal Tolak -->
                                            <div class="modal fade" id="tolak<?=$idBarangMasuk;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin untuk menolak permintaan barang masuk tersebut?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idBarangMasuk" value="<?=$idBarangMasuk;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="hapusBarangMasuk">Tolak</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
											<!-- /Modal Tolak -->

                                            <!-- Modal Terima -->
                                            <div class="modal fade" id="terima<?=$idBarangMasuk;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin untuk menerima permintaan barang masuk tersebut?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idBarangMasuk" value="<?=$idBarangMasuk;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success" name="terimaBarangMasuk">Terima</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
											<!-- Modal Terima -->	
                                        </tbody>
                                    </table>
                                    <?php if($Status == 0){?>
                                        <?php 
                                            $jabatan=$_SESSION['Jabatan']=='Owner';
                                            if($jabatan){
                                        ?>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#terima<?=$idBarangMasuk;?>"><i class="fas fa-check">Terima</i></button>
                                            <button class="btn btn-danger ml-2" type="button" data-toggle="modal" data-target="#tolak<?=$idBarangMasuk;?>"><i class="fas fa-times">Tolak</i></button>
                                        </div>
                                        <?php 
                                            }
                                        ?>
                                    <?php }else{?>
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