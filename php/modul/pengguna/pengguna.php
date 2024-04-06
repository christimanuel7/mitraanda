<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';

    //Mengecek Jabatan yang Dapat Mengakses Halaman Pengguna
    if($_SESSION['Jabatan']=='Penjaga Toko' || $_SESSION['Jabatan']=='Checker'){
        header('location:../../index.php');
    }
	
    $_SESSION['tambah']='false';
    $_SESSION['ubah']='false';
    $_SESSION['nonaktif']='false';
    $_SESSION['pulihkan']='false';
    $_SESSION['gagal']='false';

    // Proses Menambah Pengguna, Ketika Data Pengguna Ditambah
    if(isset($_POST['tambahPengguna'])){
        $Username= $_POST['Username'];
        $Nama= $_POST['Nama'];
        $Password=$_POST['Password'];
        $Jabatan= $_POST['Jabatan'];
        $noTelepon= $_POST['noTelepon'];
        $Status= '1';
        $tambahPengguna = mysqli_query($this->conn, "INSERT INTO tbpengguna (Username,Nama,Password,Jabatan,noTelepon,Status) VALUES ('$Username','$Nama','$Password','$Jabatan','$noTelepon','$Status')");

        // Kueri menambah data pengguna
        if($tambahPengguna){
            $_SESSION['tambah']='true';
        }
        else{
            $_SESSION['gagal']='true';
        } 
    }
		
    // Proses Mengubah Pengguna, Ketika Data Pengguna Diubah
    if(isset($_POST['ubahPengguna'])){
        $idPengguna= $_POST['idPengguna'];
        $Username= $_POST['Username'];
        $Nama= $_POST['Nama'];
        $Jabatan= $_POST['Jabatan'];
        $noTelepon= $_POST['noTelepon'];
        $Status= '1';
        $ubahPengguna = mysqli_query($this->conn,"UPDATE tbpengguna SET Username='$Username',Nama='$Nama',Jabatan='$Jabatan',noTelepon='$noTelepon' WHERE idPengguna='$idPengguna'");
        
        // Kueri memgubah data pengguna
        if($ubahPengguna){
            $_SESSION['ubah']='false'; 
        }
        else{
            $_SESSION['gagal']='true';
        }
    }
	  
    // Proses Menonaktifkan Pengguna, Ketika Data Pengguna Dinonaktif
    if(isset($_POST['nonaktifPengguna'])){
        $idPengguna= $_POST['idPengguna'];
        $nonaktifPengguna = mysqli_query($this->conn, "UPDATE tbpengguna SET Status='0' WHERE idPengguna='$idPengguna'");
        
        // Kueri mengubah status data pengguna ke '0'
        if($nonaktifPengguna){
            $_SESSION['nonaktif']='false'; 
        }
        else{
            $_SESSION['gagal']='true';
        }
    }

    // Proses Memulihkan Pengguna, Ketika Data Pengguna Dipulihkan
    if(isset($_POST['pulihkanPengguna'])){
        $idPengguna= $_POST['idPengguna'];
        $pulihkanPengguna = mysqli_query($this->conn, "UPDATE tbpengguna SET Status='1' WHERE idPengguna='$idPengguna'");

        // Kueri mengubah status data pengguna ke '1'
        if($pulihkanPengguna){
            $_SESSION['pulihkan']='true'; 
        }
        else{
            $_SESSION['gagal']='true';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Tag Head HTML -->
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

        <!-- Bootstrap Core CSS -->
        <link href="../../../css/sb-admin-2.css" rel="stylesheet">

        <!--Kustomisasi CSS Bootstrap dan Datatables-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    </head>
    <!-- Akhiran Tag Head HTML -->

    <!-- Tag Body HTML -->
    <body id="page-top">
        <!-- Page Wrapper -->
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

                <?php 
                    $jabatan=$_SESSION['Jabatan']=='Owner';
                    $jabatan2=$_SESSION['Jabatan']=='Checker';
                    if($jabatan OR $jabatan2){
                ?>
                    <!-- Nav Item - Master -->
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
                    <?php 
                }?>

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
                            <a class="collapse-item" href="pengguna.php"><i class="fas fa-fw fa-users"></i>Kelola Data Pengguna</a>
                            <?php 
                            }?> 
                            <a class="collapse-item" href="ubahpassword.php"><i class="fas fa-fw fa-lock"></i>Ubah Password</a>
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
                            <a class="collapse-item" href="../laporan/laporanbarangretur.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Retur</a>
                            <?php 
                                }
                            ?>
                            <?php 
                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                $jabatan2=$_SESSION['Jabatan']=='Checker';
                                if($jabatan OR $jabatan2){
                            ?>
                            <a class="collapse-item" href="../laporan/laporanopnamebarang.php"><i class="fas fa-fw fa-bars"></i>Laporan Opname Barang</a>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>
                </li> 

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
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
                                <!-- Dropdown - User Information -->
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
                        <h1 class="h3 mb-2 text-gray-800">Tabel Data Pengguna</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data pengguna berhasil ditambah.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Data pengguna berhasil diubah.
                                </div>';
                            }else if($_SESSION['nonaktif']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data pengguna berhasil dinonaktifkan.
                                </div>';
                            }else if($_SESSION['pulihkan']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data pengguna berhasil dipulihkan.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-secondary" role="alert">
                                    Data pengguna tidak terkoneksi.
                                </div>';
                            }        
                        ?>  
                       
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">
                                    <i class="fas fa-plus">Tambah</i>
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pulihkan">
                                    <i class="fas fa-check">Pulihkan</i>
                                </button>
                                <br><br>
                                <!-- Modal Tambah -->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="message-text" class="col-form-label"><b>Username:</b></label>
                                                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Masukkan Username Pengguna" minlength="8" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini sebanyak 8 karakter atau lebih!')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message-text" class="col-form-label">Nama:</label>
                                                        <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama Pengguna" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">Nomor Telepon:</label>
                                                        <input type="tel" class="form-control" id="noTelepon" name="noTelepon" placeholder="Masukkan Nomor Telepon" minlength="10" maxlength="13" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini sebanyak 10 hingga 13 digit!')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Jabatan:</label>
                                                        <select class="form-control" id="exampleFormControlSelect1" id="Jabatan" name="Jabatan" required>
                                                            <option value="">Pilih Jabatan</option>
                                                            <option value="Owner">Owner</option>
                                                            <option value="Checker">Checker</option>
                                                            <option value="Penjaga Toko">Penjaga Toko</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword1">Password:</label>
                                                        <input type="password" class="form-control" id="Password" name="Password" minlength="8" placeholder="Masukkan Password" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini sebanyak 8 karakter atau lebih!')" required>
                                                        <div class="form-check">
                                                            <input type="checkbox" onclick="showTambahPassword()" class="form-check-input" id="show">Lihat Password
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary" name="tambahPengguna">Tambah</button>
                                                </div>
                                            </form> 
                                            <script>
                                                function showTambahPassword() {
                                                    var x = document.getElementById("Password");
                                                    if (x.type === "password") {
                                                        x.type = "text";
                                                    } else {
                                                        x.type = "password";
                                                    }
                                                }
                                            </script>               
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Pulihkan -->
                                <div class="modal fade" id="pulihkan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Pulihkan Data Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                    <div class="modal-body">
                                                            <label for="exampleFormControlSelect1">Pengguna:</label>
                                                            <select class="form-control selectpicker" title="Pilih Pengguna" data-live-search="true" id="idPengguna" name="idPengguna" required>
                                                            <?php
                                                                $query    =mysqli_query($conn, "SELECT * FROM tbpengguna WHERE Status='0' ORDER BY idPengguna");
                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                                                <option value="<?=$data['idPengguna'];?>"><?php echo $data['idPengguna'].' - '.$data['Nama'];?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                            </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success" name="pulihkanPengguna">Pulihkan</button>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datatables -->
                                <div id="datatables">
                                    <table class="table table-stripped table-bordered table-hover" id="dtPengguna" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">ID Pengguna</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Username</th>
                                                <th class="text-center">No.Telepon</th>
                                                <th class="text-center">Jabatan</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDataPengguna= mysqli_query($conn,"SELECT * FROM tbpengguna WHERE tbpengguna.Status=1");
                                                while($data=mysqli_fetch_array($tampilDataPengguna)){
                                                    $idPengguna = $data['idPengguna'];
                                                    $Nama = $data['Nama'];
                                                    $Username =$data['Username'];
                                                    $noTelepon=$data['noTelepon'];
                                                    $Jabatan =$data['Jabatan'];
                                                    $Password =$data['Password'];
                                                    
                                            ?>
                                            <tr>
                                                <td class="text-center" style="width:15%"><?=$idPengguna;?></td>
                                                <td><?=$Nama;?></td>
                                                <td><?=$Username;?></td>
                                                <td><?=$noTelepon;?></td>
                                                <td><?=$Jabatan;?></td>
                                                <td>
                                                    <?php if($Jabatan=='Owner'){?>
                                                        <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#info<?=$idPengguna;?>">
                                                            <i class="fas fa-info"> Info</i>
                                                        </button>
                                                    <?php }else{?>
                                                        <button type="button" class="btn btn-warning btn-sm mb-4" data-toggle="modal" data-target="#ubah<?=$idPengguna;?>">
                                                            <i class="fas fa-edit">Ubah</i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm mb-4" data-toggle="modal" data-target="#nonaktif<?=$idPengguna;?>">
                                                            <i class="fas fa-trash">Nonaktif</i> 
                                                        </button>
                                                    <?php }?>
                                                    
                                                </td>
                                            </tr>

                                                <!-- Modal Info -->
                                                <div class="modal fade" id="info<?=$idPengguna;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Info Data Owner</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST">
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="recipient-name" class="col-form-label"><b>ID Pengguna:</b></label>
                                                                        <input type="text" class="form-control" id="idPengguna" name="idPengguna" value="<?php echo $idPengguna;?>" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="message-text" class="col-form-label">Username:</label>
                                                                        <input type="text" class="form-control" id="Username" name="Username" value="<?php echo $Username;?>" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="message-text" class="col-form-label">Nama:</label>
                                                                        <input type="text" class="form-control" id="Nama" name="Nama" value="<?php echo $Nama;?>" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="phone">Nomor Telepon:</label>
                                                                        <input type="tel" class="form-control" id="noTelepon" name="noTelepon" value="<?php echo $noTelepon;?>" minlength="10" maxlength="13" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlSelect1">Jabatan:</label>
                                                                        <input type="text" class="form-control" id="Jabatan" name="Jabatan" value="<?php echo $Jabatan;?>"readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputPassword1">Password</label>
                                                                        <input type="password" class="form-control" id="editPassword" name="editPassword" placeholder="editPassword" value="<?php echo $Password;?>" disabled>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" onclick="showEditPassword()" class="form-check-input" id="show">Lihat Password
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idPengguna" value="<?=$idPengguna;?>">
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Tutup</button>
                                                                </div>
                                                            </form>
                                                            <script>
                                                                $('#ubah<?=$idPengguna;?>').on('hidden.bs.modal', function () {
                                                                    $(this).find('form').trigger('reset');
                                                                })
                                                            </script>                
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Ubah -->
                                                <div class="modal fade" id="ubah<?=$idPengguna;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Ubah Data Pengguna</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST">
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="recipient-name" class="col-form-label"><b>ID Pengguna:</b></label>
                                                                        <input type="text" class="form-control" id="idPengguna" name="idPengguna" placeholder="Masukkan ID Pengguna" value="<?php echo $idPengguna;?>" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="message-text" class="col-form-label">Username:</label>
                                                                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Masukkan Username" minlength="8" value="<?php echo $Username;?>" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="message-text" class="col-form-label">Nama:</label>
                                                                        <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama" value="<?php echo $Nama;?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="phone">Nomor Telepon:</label>
                                                                        <input type="tel" class="form-control" id="noTelepon" name="noTelepon" placeholder="Masukkan Nomor Telepon" value="<?php echo $noTelepon;?>" minlength="10" maxlength="13" oninvalid="this.setCustomValidity('Masukkan nomor telepon pada kolom pengisian ini sebanyak 10 hingga 13 digit!')" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlSelect1">Jabatan:</label>
                                                                        <select class="form-control" id="exampleFormControlSelect1" id="Jabatan" name="Jabatan">
                                                                            <?php
                                                                                $querySelect=mysqli_query($conn, "SELECT * FROM tbpengguna WHERE idPengguna='$idPengguna'");
                                                                                $dataSelect=mysqli_fetch_array($querySelect);
                                                                            ?>
                                                                            <option value="<?php echo $dataSelect['Jabatan'];?>" hidden><?php echo $dataSelect['Jabatan'];?></option>
                                                                            <option value="Owner">Owner</option>
                                                                            <option value="Checker">Checker</option>
                                                                            <option value="Penjaga Toko">Penjaga Toko</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputPassword1">Password</label>
                                                                        <input type="password" class="form-control" id="passwordEdit" name="passwordEdit" minlength="8" placeholder="editPassword" value="<?php echo $Password;?>" disabled>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" class="form-check-input" id="show" onclick="checkPasswordEdit()">Lihat Password
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idPengguna" value="<?=$idPengguna;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-warning" name="ubahPengguna">Ubah</button>
                                                                </div>
                                                            </form>
															 <script>
																function checkPasswordEdit() {
																	var x = document.getElementById("passwordEdit");
																	if (x.type === "password") {
																		x.type = "text";
																	} else {
																		x.type = "password";
																	}
																}
															</script>
                                                            <script>
                                                                $('#ubah<?=$idPengguna;?>').on('hidden.bs.modal', function () {
                                                                    $(this).find('form').trigger('reset');
                                                                })
                                                            </script>                
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Nonaktif -->
                                                <div class="modal fade" id="nonaktif<?=$idPengguna;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin ingin menonaktifkan data ini?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php echo $idPengguna.' - '.$Nama.' ('.$Jabatan.')';?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idPengguna" value="<?=$idPengguna;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="nonaktifPengguna">Nonaktifkan</button>
                                                                </div>
                                                            </div>
                                                        </form>     
                                                    </div>
                                                </div>
                                            <?php
                                                };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    if ( window.history.replaceState ) {
                        window.history.replaceState( null, null, window.location.href );
                    }
                </script>
                <!-- Akhiran Main Content -->

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
        <!-- Akhiran Page Wrapper -->

        <!-- Scroll to Top Button-->
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
            $('#dtPengguna').DataTable( {
                lengthMenu: [25, 50, 100, 200, 500],
                scrollX:true,
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