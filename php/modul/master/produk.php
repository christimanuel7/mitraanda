<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';
    
    $_SESSION['tambah']='false';
    $_SESSION['gagal']='false';
    $_SESSION['ubah']='false';

    // Proses Menambah Produk, Ketika Data Produk Ditambah
    if(isset($_POST['tambahProduk'])){
        $idSatuan= $_POST['idSatuan'];
        $Produk= $_POST['Produk'];
        $hargaBeli= $_POST['hargaBeli'];
        $hargaJual= $_POST['hargaJual'];
        $Status= '1';
        $tambahProduk = mysqli_query($conn, "INSERT INTO tbproduk (Produk,idSatuan,hargaBeli,hargaJual,stokProduk,Status) VALUES ('$Produk','$idSatuan','$hargaBeli','hargaJual','0','$Status')");
        
        // Kueri menambah data produk
        if($tambahProduk){
            $_SESSION['tambah']= 'true';
        }else{
            $_SESSION['gagal']='false';
        } 
    }
		
    // Proses Memulihkan Produk, Ketika Data Produk Dipulihkan
    if(isset($_POST['pulihkanProduk'])){
        $idProduk= $_POST['idProduk'];
        $pulihkanProduk = mysqli_query($conn,"UPDATE tbproduk SET Status='1' WHERE idProduk='$idProduk'");
        
        // Kueri mengubah status data produk ke '1'
        if($pulihkanProduk){
            $_SESSION['pulihkan']='true';
        }else{
            $_SESSION['gagal']='false';
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
                            <?php 
                                $jabatan1=$_SESSION['Jabatan']=='Owner';
                                $jabatan2=$_SESSION['Jabatan']=='Checker';
                                if($jabatan1 OR $jabatan2){
                            ?>
                            <a class="collapse-item" href="../barangmasuk/databarangmasuk.php"><i class="fas fa-fw fa-arrow-down"></i>Barang Masuk</a>
                            <?php 
                            }?>
                            <?php 
                                $jabatan1=$_SESSION['Jabatan']=='Owner';
                                $jabatan2=$_SESSION['Jabatan']=='Penjaga Toko';
                                if($jabatan1 OR $jabatan2){
                            ?>
                            <a class="collapse-item" href="../barangkeluar/databarangkeluar.php"><i class="fas fa-fw fa-arrow-up"></i>Barang Keluar</a>
							<a class="collapse-item" href="../retur/databarangretur.php"><i class="fas fa-fw fa-retweet"></i>Retur Barang</a>
                            <?php 
                            }?>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Opname- -->
				<?php 
					$jabatan1=$_SESSION['Jabatan']=='Owner';
					$jabatan2=$_SESSION['Jabatan']=='Checker';
					if($jabatan1 OR $jabatan2){
				?>
                <li class="nav-item">
                    <a class="nav-link" href="../opname/dataopname.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Opname Stok</span></a>
                </li>
				<?php 
                }?>

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

                    <!-- Nav Item - Laporan-->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                        aria-expanded="true" aria-controls="collapseLaporan">
                            <i class="fas fa-fw fa-clipboard-list"></i>
                            <span>Laporan</span>
                        </a>       
                        <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <?php 
                                    $jabatan1=$_SESSION['Jabatan']=='Owner';
                                    $jabatan2=$_SESSION['Jabatan']=='Checker';
                                    if($jabatan1 OR $jabatan2){
                                ?>
                                <a class="collapse-item" href="../laporan/laporanbarangmasuk.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Masuk</a>
                                <?php 
								}?>
                                <?php 
                                    $jabatan1=$_SESSION['Jabatan']=='Owner';
                                    $jabatan2=$_SESSION['Jabatan']=='Penjaga Toko';
                                    if($jabatan1 OR $jabatan2){
                                ?>
                                <a class="collapse-item" href="../laporan/laporanbarangkeluar.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Keluar</a>
                                <a class="collapse-item" href="../laporan/laporanbarangretur.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Retur</a>
                                <?php 
								}?>
								<?php 
									$jabatan=$_SESSION['Jabatan']=='Owner';
									$jabatan2=$_SESSION['Jabatan']=='Checker';
									if($jabatan OR $jabatan2){
								?>
								<a class="collapse-item" href="../laporan/laporanopnamebarang.php"><i class="fas fa-fw fa-bars"></i>Laporan Opname Barang</a>
								<?php 
								}?> 
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
                    </nav>
                    <!-- Akhiran Topbar -->

                    <!-- Page Content -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800">Tabel Data Produk</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data produk berhasil ditambah.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data produk berhasil diubah.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data produk tidak terkoneksi.
                                </div>';
                            }
                        ?>      
                        <div class="row">
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Stok Habis</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        $dataProduk = mysqli_query($conn,"SELECT * FROM tbproduk WHERE stokProduk=0");
                                                        
                                                        $jumlahProduk = mysqli_num_rows($dataProduk);
                                                    ?>
                                                    <?php echo $jumlahProduk; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-fw fa-times fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" data-toggle="modal" data-target="#habis">
                                    <div class="row">
                                            <div class="col-10">
                                                Detail Stok Habis
                                            </div>
                                            <div class="col">
                                                <i class="fas fa-fw fa-arrow-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Stok Kurang dari 5</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        $dataProduk = mysqli_query($conn,"SELECT * FROM tbproduk WHERE stokProduk<5 AND stokProduk>0");
                                                        
                                                        $jumlahProduk = mysqli_num_rows($dataProduk);
                                                    ?>
                                                    <?php echo $jumlahProduk; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-exclamation-triangle fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" data-toggle="modal" data-target="#kritis">
                                        <div class="row">
                                            <div class="col-10">
                                                Detail Stok Kurang dari 5
                                            </div>
                                            <div class="col">
                                                <i class="fas fa-fw fa-arrow-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <?php 
                                    $jabatan=$_SESSION['Jabatan']=='Owner';
                                    if($jabatan){
                                ?>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">
                                        <i class="fas fa-plus">Tambah</i>
                                    </button>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pulihkan">
                                        <i class="fas fa-check">Pulihkan</i>
                                    </button>
                                <?php 
                                    }
                                ?>
                                <a class="btn btn-secondary" href="../laporan/cetaklaporanproduk.php" target="_blank">
                                    <i class="fas fa-print">Cetak</i>
                                </a>
                                <br><br>
                                <!-- Modal Tambah -->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Produk</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Nama Produk:</label>
                                                        <input type="text" class="form-control" id="Produk" name="Produk" placeholder="Masukkan Nama Produk" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Satuan:</label>
                                                        <select class="form-control selectpicker" id="exampleFormControlSelect1" title="Pilih Satuan" data-live-search="true" data-size="5" id="idSatuan" name="idSatuan" oninvalid="this.setCustomValidity('Pilih item pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                            <?php
                                                                $query    =mysqli_query($conn, "SELECT * FROM tbsatuan ORDER BY idSatuan");
                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                                                <option value="<?=$data['idSatuan'];?>"><?php echo $data['Satuan'];?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">Harga Jual per Unit:</label>
                                                        <input type="number" class="form-control" id="hargaJual" name="hargaJual" min="0" oninput="validity.valid||(value='');" placeholder="Masukkan Harga Jual per Unit" oninvalid="this.setCustomValidity('Masukkan harga jual per unit pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary" name="tambahProduk">Tambah</button>
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
                                                <h5 class="modal-title" id="exampleModalLabel">Pulihkan Data Produk</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                    <div class="modal-body">
                                                            <label for="exampleFormControlSelect1">Nama Produk:</label>
                                                            <select class="form-control selectpicker" id="exampleFormControlSelect1" title="Pilih Produk" data-live-search="true" id="idProduk" id="idProduk" name="idProduk" oninvalid="this.setCustomValidity('Pilih item pada kolom pengisian ini!')" required>
                                                                <?php
                                                                    $query    =mysqli_query($conn, "SELECT * FROM tbproduk WHERE Status='0' ORDER BY idProduk");
                                                                    while ($data = mysqli_fetch_array($query)) {
                                                                    ?>
                                                                    <option value="<?=$data['idProduk'];?>"><?php echo $data['idProduk'].' - '.$data['Produk'];?></option>
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success" name="pulihkanProduk">Pulihkan</button>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 

                                <!-- Datatables -->
                                <div id="datatables">
                                    <table class="table table-stripped table-bordered table-hover" id="dtProduk" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">ID Produk</th>
                                                <th class="text-center">Nama Produk</th>
                                                <th class="text-center">Satuan</th>
                                                <th class="text-center">Harga Jual</th>
                                                <th class="text-center">Stok</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDataProduk= mysqli_query($conn,"SELECT * FROM tbproduk
                                                INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                WHERE tbproduk.Status = '1'
                                                ORDER BY idProduk;");
                                                while($data=mysqli_fetch_array($tampilDataProduk)){
                                                    $idProduk = $data['idProduk'];
                                                    $Produk =$data['Produk'];
                                                    $idSatuan =$data['idSatuan'];
                                                    $Satuan =$data['Satuan'];
                                                    $hargaJual=$data['hargaJual'];
                                                    $konversiHargaJual = "Rp " . number_format($data['hargaJual'],2,',','.');
                                                    $stokProduk =$data['stokProduk'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$idProduk;?></td>
                                                <td><?=$Produk;?></td>
                                                <td class="text-center"><?=$Satuan;?></td>
                                               
                                                <td class="text-center"><?=$konversiHargaJual;?></td>
                                                <td class="text-center"><?=$stokProduk;?></td>
                                                <td class="text-center">
                                                    <a type="button" class="btn btn-warning btn-sm mb-4" href="detailproduk.php?id=<?php echo $idProduk;?>">
                                                        <i class="fas fa-edit">Ubah</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                };
                                            ?>

                                            <!-- Modal Habis -->
                                            <div class="modal fade" id="habis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="exampleModalLabel">List Stok Habis:</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body" style="padding-right:80px;">
                                                            <ul style="list-style-type: none;">
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-3 text-center"><b>ID Produk</b></div>
                                                                        <div class="col-6 text-center"><b>Nama Produk</b></div>
                                                                        <div class="col-3 text-center"><b>Sisa Stok</b></div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <?php
                                                                $tampilDataProduk= mysqli_query($conn,"
                                                                SELECT idProduk, Satuan, Produk, stokProduk
                                                                FROM tbproduk
                                                                INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                                WHERE tbproduk.Status = '1' AND tbproduk.stokProduk=0
                                                                ORDER BY idProduk;");
                                                                while($data=mysqli_fetch_array($tampilDataProduk)){
                                                                    $idProduk = $data['idProduk'];
                                                                    $Produk =$data['Produk'];
                                                                    $Satuan =$data['Satuan'];
                                                                    $stokProduk =$data['stokProduk'];
                                                            ?>
                                                            <ul style="list-style-type: none;">
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-3 text-center"><?=$idProduk;?></div>
                                                                        <div class="col-6 text-center"><?=$Produk;?></div>
                                                                        <div class="col-3 text-center"><?=$stokProduk;?></div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                                <?php
                                                                };
                                                            ?>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Kritis -->
                                            <div class="modal fade" id="kritis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title" id="exampleModalLabel">List Stok Kurang dari 5:</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body" style="padding-right:80px;">
                                                            <ul style="list-style-type: none;">
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-3 text-center"><b>ID Produk</b></div>
                                                                        <div class="col-6 text-center"><b>Nama Produk</b></div>
                                                                        <div class="col-3 text-center"><b>Sisa Stok</b></div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <?php
                                                                $tampilDataProduk= mysqli_query($conn,"
                                                                SELECT idProduk, Satuan, Produk, stokProduk
                                                                FROM tbproduk
                                                                INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                                WHERE tbproduk.Status = '1' AND tbproduk.stokProduk<5 AND tbproduk.stokProduk>0
                                                                ORDER BY stokProduk;");
                                                                while($data=mysqli_fetch_array($tampilDataProduk)){
                                                                    $idProduk = $data['idProduk'];
                                                                    $Produk =$data['Produk'];
                                                                    $Satuan =$data['Satuan'];
                                                                    $stokProduk =$data['stokProduk'];
                                                            ?>
                                                            <ul style="list-style-type: none;">
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-3 text-center"><?=$idProduk;?></div>
                                                                        <div class="col-6 text-center"><?=$Produk;?></div>
                                                                        <div class="col-3 text-center"><?=$stokProduk;?></div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                                <?php
                                                                };
                                                            ?>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
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
			
			<script>
				if ( window.history.replaceState ) {
					window.history.replaceState( null, null, window.location.href );
				}
			</script>
            <!-- Akhiran Content Wrapper -->
        </div>
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
            $('#dtProduk').DataTable( {
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