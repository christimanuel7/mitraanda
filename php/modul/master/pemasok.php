<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';
	
    //Proses Mengecek Jabatan yang Dapat Mengakses Halaman Pemasok
    if($_SESSION['Jabatan']=='Penjaga Toko' || $_SESSION['Jabatan']=='Checker'){
        header('location:../../index.php');
    }

    $_SESSION['tambah']='false';
    $_SESSION['ubah']='false';
    $_SESSION['nonaktif']='false';
    $_SESSION['pulihkan']='false';
    $_SESSION['gagal']='false';
		
    // Proses Menambah Pemasok, Ketika Data Pemasok Ditambah
    if(isset($_POST['tambahPemasok'])){
        $Pemasok= $_POST['Pemasok'];
        $Alamat= $_POST['Alamat'];
        $noTelepon= $_POST['noTelepon'];
        $contactPerson= $_POST['contactPerson'];
        $Status= '1';
        $tambahPemasok = mysqli_query($conn, "INSERT INTO tbpemasok (Pemasok,Alamat,noTelepon,contactPerson,Status) VALUES ('$Pemasok','$Alamat','$noTelepon','$contactPerson','$Status')");
    
        // Kueri menambah data pemasok
        if($tambahPemasok){
            $_SESSION['tambah']='true';
        }else{
            $_SESSION['gagal']='true';
        } 
    }
		
    // Proses Mengubah Pemasok, Ketika Data Pemasok Diubah
    if(isset($_POST['ubahPemasok'])){
        $idPemasok= $_POST['idPemasok'];
        $Pemasok= $_POST['Pemasok'];
        $Alamat= $_POST['Alamat'];
        $noTelepon= $_POST['noTelepon'];
        $contactPerson= $_POST['contactPerson'];
        $Status= '1';
        $ubahPemasok = mysqli_query($conn,"UPDATE tbpemasok SET Pemasok='$Pemasok',Alamat='$Alamat',noTelepon='$noTelepon',contactPerson='$contactPerson' WHERE idPemasok='$idPemasok'");
    
        // Kueri mengubah data pemasok
        if($ubahPemasok){
            $_SESSION['ubah']='true';
        }else{
            $_SESSION['gagal']='true';
        } 
    }
		
    // Proses Menonaktifkan Pemasok, Ketika Data Pemasok Dinonaktif
    if(isset($_POST['nonaktifPemasok'])){
        $idPemasok= $_POST['idPemasok'];
        $Status= '0';
        $nonaktifPemasok = mysqli_query($conn,"UPDATE tbpemasok SET Status='$Status' WHERE idPemasok='$idPemasok'");
        
        // Kueri mengubah status data pemasok ke '0'
        if($nonaktifPemasok){
            $_SESSION['nonaktif']='true';
        }else{
            $_SESSION['gagal']='true';
        }
    }
	
    // Proses Memulihkan Pemasok, Ketika Data Pemasok Dipulihkan
    if(isset($_POST['pulihkanPemasok'])){
        $idPemasok= $_POST['idPemasok'];
        $Status='1';
        $pulihkanPemasok = mysqli_query($conn,"UPDATE tbpemasok SET Status='$Status' WHERE idPemasok='$idPemasok'");
        
        // Kueri mengubah status data pemasok ke '1'
        if($pulihkanPemasok){
            $_SESSION['pulihkan']='true'; 
        }else{
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

        <!--Kustomisasi CSS Bootstrap dan Datatables-->
        <link href="../../../css/sb-admin-2.css" rel="stylesheet">
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
            <!-- Akhiran Sidebar -->
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

                <!-- Nav Item - Master -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
                    aria-expanded="true" aria-controls="collapseMaster">
                        <i class="fas fa-fw fa-desktop"></i>
                        <span>Master Data</span>
                    </a>               
                    <div id="collapseMaster" class="collapse" aria-labelledby="headingMaster" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="produk.php"><i class="fas fa-fw fa-box"></i>Produk</a>
                            <?php 
                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                if($jabatan){
                            ?>
                            <a class="collapse-item" href="pemasok.php"><i class="fas fa-fw fa-truck"></i>Pemasok</a>
                            <a class="collapse-item" href="satuan.php"><i class="fas fa-fw fa-weight-hanging"></i>Satuan</a>
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
                            <a class="collapse-item" href="../barangmasuk/databarangmasuk.php"><i class="fas fa-fw fa-arrow-down"></i>Barang Masuk</a>
                            <a class="collapse-item" href="../barangkeluar/databarangkeluar.php"><i class="fas fa-fw fa-arrow-up"></i>Barang Keluar</a>
							<a class="collapse-item" href="../retur/databarangretur.php"><i class="fas fa-fw fa-retweet"></i>Retur Barang</a>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Opname- -->
                <li class="nav-item">
                    <a class="nav-link" href="../opname/dataopname.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Opname Stok</span></a>
                </li>

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
                                }
                            ?>
                            <a class="collapse-item" href="../pengguna/ubahpassword.php"><i class="fas fa-fw fa-lock"></i>Ubah Password</a>
                        </div>
                    </div>
                </li> 

                <?php 
                    $jabatan=$_SESSION['Jabatan']=='Owner';
                    $jabatan2=$_SESSION['Jabatan']=='Checker';
                    if($jabatan OR $jabatan2){
                ?>
                     <!-- Nav Item - Laporan -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                        aria-expanded="true" aria-controls="collapseLaporan">
                            <i class="fas fa-fw fa-clipboard-list"></i>
                            <span>Laporan</span>
                        </a>
                            
                        <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="../laporan/laporanbarangmasuk.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Masuk</a>
                                <a class="collapse-item" href="../laporan/laporanbarangkeluar.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Keluar</a>
                                <a class="collapse-item" href="../laporan/laporanbarangretur.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Retur</a>
								<a class="collapse-item" href="../laporan/laporanopnamebarang.php"><i class="fas fa-fw fa-bars"></i>Laporan Opname Barang</a>
                            </div>
                        </div>
                    </li>
                <?php 
                }?> 

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
                    </nav>
                    <!-- End of Topbar -->

                    <!-- Page Content -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800">Tabel Data Pemasok</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data pemasok berhasil ditambah.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Data pemasok berhasil diubah.
                                </div>';
                            }else if($_SESSION['nonaktif']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data pemasok berhasil dinonaktifkan.
                                </div>';
                            }else if($_SESSION['pulihkan']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data pemasok berhasil dipulihkan.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data pemasok tidak terkoneksi.
                                </div>';
                            }    
                        ?>      
                        <!-- DataTales Example -->
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
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pemasok</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="message-text" class="col-form-label">Nama Pemasok:</label>
                                                        <input type="text" class="form-control" id="Pemasok" name="Pemasok" placeholder="Masukkan Nama Pemasok" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Alamat Pemasok:</label>
                                                        <textarea class="form-control" id="Alamat" name="Alamat" rows="5" placeholder="Masukkan Alamat Pemasok" style="resize: none;"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">Nomor Telepon:</label>
                                                        <input type="tel" class="form-control" id="noTelepon" name="noTelepon" minlength="10" maxlength="13" placeholder="Masukkan Nomor Telepon"  oninvalid="this.setCustomValidity('Masukkan nomor telepon pada kolom pengisian ini sebanyak 10-13 digit!')"  onchange="this.setCustomValidity('')">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message-text" class="col-form-label">Contact Person:</label>
                                                        <input type="text" class="form-control" id="contactPerson" name="contactPerson" placeholder="Masukkan Contact Person">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary" name="tambahPemasok">Tambah</button>
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

                                <!-- Modal Pulihkan -->
                                <div class="modal fade" id="pulihkan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Pulihkan Data Pemasok</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                        <label for="exampleFormControlSelect1">Nama Pemasok:</label>
                                                        <select class="form-control selectpicker" id="exampleFormControlSelect1" data-live-search="true" title="Pilih Pemasok" id="idPemasok" name="idPemasok" oninvalid="this.setCustomValidity('Pilih item pada kolom pengisian ini!')" required>
                                                        <?php
                                                            $query    =mysqli_query($conn, "SELECT * FROM tbpemasok WHERE Status='0' ORDER BY idPemasok");
                                                            while ($data = mysqli_fetch_array($query)) {
                                                            ?>
                                                            <option value="<?=$data['idPemasok'];?>"><?php echo $data['idPemasok'].' - '.$data['Pemasok'];?></option>
                                                            <?php
                                                            }
                                                        ?>
                                                        </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success" name="pulihkanPemasok">Pulihkan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="datatables">
                                    <table class="table table-stripped table-bordered table-hover" id="dtPemasok" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">ID Pemasok</th>
                                                <th class="text-center">Pemasok</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">No.Telepon</th>
                                                <th class="text-center">CP</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDataPemasok= mysqli_query($conn,"SELECT * FROM tbpemasok WHERE Status=1");
                                                while($data=mysqli_fetch_array($tampilDataPemasok)){
                                                    $idPemasok = $data['idPemasok'];
                                                    $Pemasok = $data['Pemasok'];
                                                    $Alamat =$data['Alamat'];
                                                    $noTelepon =$data['noTelepon'];
                                                    $contactPerson =$data['contactPerson'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$idPemasok;?></td>
                                                <td><?=$Pemasok;?></td>
                                                <td><?=$Alamat;?></td>
                                                <td><?=$noTelepon;?></td>
                                                <td class="text-center"><?=$contactPerson;?></td>
                                                <td class="text-left" style="width:20%;">
                                                    <button type="button" class="btn btn-warning btn-sm mb-4" data-toggle="modal" data-target="#ubah<?=$idPemasok;?>">
                                                        <i class="fas fa-edit">Ubah</i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm mb-4" data-toggle="modal" data-target="#nonaktif<?=$idPemasok;?>">
                                                        <i class="fas fa-trash">Nonaktif</i> 
                                                    </button>
                                                    <?php if(!empty($noTelepon)){?>
                                                    <a type="button" class="btn btn-primary btn-sm mb-4" target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo $noTelepon;?>">
                                                        <i class="fas fa-phone">Pesan</i> 
                                                    </a>
                                                    <?php }?>
                                                </td>
                                            </tr>

                                            <!-- Modal Ubah-->
                                            <div class="modal fade" id="ubah<?=$idPemasok;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Ubah Data Pemasok</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="recipient-name" class="col-form-label">ID Pemasok:</label>
                                                                    <input type="text" class="form-control" id="idPemasok" name="idPemasok" value="<?php echo $idPemasok;?>" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="message-text" class="col-form-label">Nama Pemasok:</label>
                                                                    <input type="text" class="form-control" id="Pemasok" name="Pemasok" value="<?php echo $Pemasok;?>" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlTextarea1">Alamat Pemasok:</label>
                                                                    <textarea class="form-control" id="Alamat" name="Alamat" rows="5" value="<?php echo $Alamat;?>" placeholder="Masukkan Alamat" style="resize: none;"><?php echo $Alamat;?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="phone">Nomor Telepon:</label>
                                                                    <input type="tel" class="form-control" id="noTelepon" name="noTelepon" minlength="10" maxlength="13" placeholder="Masukkan Nomor Telepon" value="<?php echo $noTelepon;?>" oninvalid="this.setCustomValidity('Masukkan nomor telepon pada kolom pengisian ini sebanyak 10-13 digit!')"  onchange="this.setCustomValidity('')">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="message-text" class="col-form-label">Contact Person:</label>
                                                                    <input type="text" class="form-control" id="contactPerson" name="contactPerson" value="<?php echo $contactPerson;?>" placeholder="Masukkan Contact Person">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idAdmin" value="<?=$idPemasok;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning" name="ubahPemasok">Ubah</button>
                                                            </div>
                                                        </form>
                                                        <script>
                                                        $('#ubah<?=$idPemasok;?>').on('hidden.bs.modal', function () {
                                                            $(this).find('form').trigger('reset');
                                                        })
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>

                                        <!-- Modal Nonaktif -->
                                        <div class="modal fade" id="nonaktif<?=$idPemasok;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin ingin menonaktifkan data ini?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php echo $idPemasok.' - '.$Pemasok;?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idPemasok" value="<?=$idPemasok;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger" name="nonaktifPemasok">Nonaktifkan</button>
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
                    <!-- /.container-fluid -->

                </div>
                <script>
                    if ( window.history.replaceState ) {
                        window.history.replaceState( null, null, window.location.href );
                    }
                </script>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; TB. Mitra Anda 2023</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- Akhiran Content Wrapper -->
        </div>
        <!-- Akhiran Page Wrapper -->

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
            $('#dtPemasok').DataTable( {
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