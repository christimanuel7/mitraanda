<?php
    require 'fungsi.php';
    require 'ceklogin.php';
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
        <link rel = "icon" href = "../assets/img/logo.png" type = "image/png">

        <!-- Kustomisasi Font Template-->
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!--Kustomisasi CSS Bootstrap-->
        <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    </head>
    <!-- Akhiran Tag Head HTML -->

    <!-- Tag Body HTML -->
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                    <img class="img-fluid" src="../assets/img/logotext.png"></img>
                </a>

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Dashboard</span></a>
                </li>

                <div class="sidebar-heading">
                    Antarmuka
                </div>

                <!-- Nav Item - Master Data- -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
                    aria-expanded="true" aria-controls="collapseMaster">
                        <i class="fas fa-fw fa-desktop"></i>
                        <span>Master Data</span>
                    </a>
                    <div id="collapseMaster" class="collapse" aria-labelledby="headingMaster" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="modul/master/produk.php"><i class="fas fa-fw fa-box"></i>Produk</a>
							<?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								if($jabatan){
							?>
                            <a class="collapse-item" href="modul/master/pemasok.php"><i class="fas fa-fw fa-truck"></i>Pemasok</a>
                            <a class="collapse-item" href="modul/master/satuan.php"><i class="fas fa-fw fa-weight-hanging"></i>Satuan</a>
							<?php 
							}?>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Stok- -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStok"
                    aria-expanded="true" aria-controls="collapseStok">
                        <i class="fas fa-fw fa-boxes"></i>
                        <span>Transaksi</span>
                    </a>
                    <div id="collapseStok" class="collapse" aria-labelledby="headingBarang" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="modul/barangmasuk/databarangmasuk.php"><i class="fas fa-fw fa-arrow-down"></i>Barang Masuk</a>
                            <a class="collapse-item" href="modul/barangkeluar/databarangkeluar.php"><i class="fas fa-fw fa-arrow-up"></i>Barang Keluar</a>
							<?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Checker';
								if($jabatan OR $jabatan2){
							?>
                            <a class="collapse-item" href="modul/retur/databarangretur.php"><i class="fas fa-fw fa-retweet"></i>Retur Barang</a>
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
                    <a class="nav-link" href="modul/opname/dataopname.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Opname Stok</span></a>
                </li>
                <?php 
                }?>

                <!-- Nav Item - Pengguna- -->
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
                            <a class="collapse-item" href="modul/pengguna/pengguna.php"><i class="fas fa-fw fa-users"></i>Kelola Data Pengguna</a>
                            <?php 
                            }?> 
                            <a class="collapse-item" href="modul/pengguna/ubahpassword.php"><i class="fas fa-fw fa-lock"></i>Ubah Password</a>
                        </div>
                    </div>
                </li> 

                <!-- Nav Item - Laporan- -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                    aria-expanded="true" aria-controls="collapseLaporan">
                        <i class="fas fa-fw fa-clipboard-list"></i>
                        <span>Laporan</span>
                    </a>
                        
                    <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="modul/laporan/laporanbarangmasuk.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Masuk</a>
                            <a class="collapse-item" href="modul/laporan/laporanbarangkeluar.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Keluar</a>
							<?php 
								$jabatan=$_SESSION['Jabatan']=='Owner';
								$jabatan2=$_SESSION['Jabatan']=='Checker';
								if($jabatan OR $jabatan2){
							?>
                            <a class="collapse-item" href="modul/laporan/laporanbarangretur.php"><i class="fas fa-fw fa-bars"></i>Laporan Barang Retur</a>
							<a class="collapse-item" href="modul/laporan/laporanopnamebarang.php"><i class="fas fa-fw fa-bars"></i>Laporan Opname Barang</a>
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
                                        src="../assets/img/undraw_profile.svg">
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
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800"><b>Dashboard</b></h1>
                        </div>
                        <div class="card mb-4">
                            <h5 class="card-header bg-secondary text-white">Selamat Datang di Halaman Dashboard Toko Bangunan Mitra Anda</h5>
                            <div class="card-body">
                                <p class="card-text">Saat ini anda mengakses halaman dashboard sebagai jabatan <?php echo $_SESSION['Jabatan']?>.</p>
                            </div>
                        </div>
                        <!-- Content Row -->
                        <div class="row">
                            <!-- Jumlah Produk -->
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Jumlah Produk</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        // mengambil data barang
                                                        $dataProduk = mysqli_query($conn,"SELECT * FROM tbproduk");
                                                        
                                                        // menghitung data barang
                                                        $jumlahProduk = mysqli_num_rows($dataProduk);
                                                    ?>
                                                    <?php echo $jumlahProduk; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-box fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah Pending Opname -->
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Konfirmasi Opname Barang</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        // mengambil data barang
                                                        $dataPermintaanOpname = mysqli_query($conn,"SELECT * FROM tbopname WHERE Status=0 GROUP BY idOpname");
                                                        
                                                        // menghitung data barang
                                                        $jumlahPermintaanOpname = mysqli_num_rows($dataPermintaanOpname);
                                                    ?>
                                                    <?php echo $jumlahPermintaanOpname; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-search fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah Pending Barang Masuk -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Konfirmasi Barang Masuk</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        // mengambil data barang
                                                        $dataPermintaanBarangMasuk = mysqli_query($conn,"SELECT * FROM tbstokmasuk WHERE Status=0 GROUP BY idBarangMasuk");;
                                                        
                                                        // menghitung data barang
                                                        $jumlahPermintaanBarangMasuk = mysqli_num_rows($dataPermintaanBarangMasuk);
                                                    ?>
                                                    <?php echo $jumlahPermintaanBarangMasuk; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-down fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah Pending Barang Keluar -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Konfirmasi Barang Keluar</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        // mengambil data barang
                                                        $dataPermintaanBarangKeluar = mysqli_query($conn,"SELECT * FROM tbstokkeluar WHERE Status=0 GROUP BY idBarangKeluar");;
                                                        
                                                        // menghitung data barang
                                                        $jumlahPermintaanBarangKeluar = mysqli_num_rows($dataPermintaanBarangKeluar);
                                                    ?>
                                                    <?php echo $jumlahPermintaanBarangKeluar; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-up fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah Pending Barang Retur-->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Konfirmasi Retur</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                    <?php 
                                                        // mengambil data barang
                                                        $dataPermintaanBarangRetur = mysqli_query($conn,"SELECT * FROM tbretur WHERE Status=0 GROUP BY idBarangRetur");
                                                        
                                                        // menghitung data barang
                                                        $jumlahPermintaanBarangRetur = mysqli_num_rows($dataPermintaanBarangRetur);
                                                    ?>
                                                    <?php echo $jumlahPermintaanBarangRetur; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-retweet fa-2x text-dark-300"></i>
                                            </div>
                                        </div>
                                    </div>
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
                    <div class="modal-body">Pilih "Keluar" dibawah ini untuk mengakhiri sesi anda</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <a class="btn btn-danger" href="logout.php">Keluar</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript Bootstrap-->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="../js/sb-admin-2.min.js"></script>
    </body>
    <!-- Akhiran Tag Body HTML -->
</html>