<?php
	require '../../fungsi.php';
	require '../../ceklogin.php';

    //Proses Mengecek Jabatan yang Dapat Mengakses Halaman Satuan
    if ($_SESSION['Jabatan'] == 'Penjaga Toko' || $_SESSION['Jabatan'] == 'Checker') {
        header('location:../../index.php');
    }
		
    $_SESSION['tambah'] = 'false';
    $_SESSION['ubah'] = 'false';
    $_SESSION['nonaktif'] = 'false';
    $_SESSION['pulihkan'] = 'false';
    $_SESSION['gagal'] = 'false';

    // Proses Menambah Satuan, Ketika Data Satuan Ditambah
    if (isset($_POST['tambahSatuan'])) {
        $Satuan = $_POST['Satuan'];
        $Status = '1';
        $tambahSatuan = mysqli_query($conn, "INSERT INTO tbsatuan (Satuan,Status) VALUES ('$Satuan','$Status')");

        if ($tambahSatuan) {
            $_SESSION['tambah'] = 'true';
        } else {
            $_SESSION['gagal'] = 'true';
        }
    }

	// Proses Mengubah Satuan, Ketika Data Satuan Diubah
    if (isset($_POST['ubahSatuan'])) {
        $idSatuan = $_POST['idSatuan'];
        $Satuan = $_POST['Satuan'];
        $ubahSatuan = mysqli_query($conn, "UPDATE tbsatuan SET Satuan='$Satuan' WHERE idSatuan='$idSatuan'");

        if ($ubahSatuan) {
            $_SESSION['ubah'] = 'true';
        } else {
            $_SESSION['gagal'] = 'true';
        }
    }

    // Proses Menonaktifkan Satuan, Ketika Data Satuan Dinonaktif
    if (isset($_POST['nonaktifSatuan'])) {
        $idSatuan = $_POST['idSatuan'];
        $Status = '0';
        $nonaktifSatuan = mysqli_query($conn, "UPDATE tbsatuan SET Status='$Status' WHERE idSatuan='$idSatuan'");

        if ($nonaktifSatuan) {
            $_SESSION['nonaktif'] = 'true';
        } else {
            $_SESSION['gagal'] = 'true';
        }
    }

    // Proses Memulihkan Satuan, Ketika Data Satuan Dipulihkan
    if (isset($_POST['pulihkanSatuan'])) {
        $idSatuan = $_POST['idSatuan'];
        $Status = '1';
        $pulihkanSatuan = mysqli_query($conn, "UPDATE tbsatuan SET Status='$Status' WHERE idSatuan='$idSatuan'");

        if ($pulihkanSatuan) {
            $_SESSION['pulihkan'] = 'true';
        } else {
            $_SESSION['gagal'] = 'true';
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
                        <h1 class="h3 mb-2 text-gray-800">Tabel Data Satuan</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data satuan berhasil ditambah.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Data satuan berhasil diubah.
                                </div>';
                            }else if($_SESSION['nonaktif']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data satuan berhasil dinonaktifkan.
                                </div>';
                            }else if($_SESSION['pulihkan']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data satuan berhasil dipulihkan.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data satuan tidak terkoneksi.
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
                                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Satuan</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                            <label for="message-text" class="col-form-label">Nama Satuan:</label>
                                                            <input type="text" class="form-control" id="Satuan" name="Satuan" placeholder="Masukkan Nama Satuan" oninvalid="this.setCustomValidity('Masukkan teks kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary" name="tambahSatuan">Tambah</button>
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
                                                <h5 class="modal-title" id="exampleModalLabel">Pulihkan Data Satuan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <label for="exampleFormControlSelect1">Nama Satuan:</label>
                                                    <select class="form-control selectpicker" data-live-search="true" title="Pilih Satuan" id="idSatuan" name="idSatuan" oninvalid="this.setCustomValidity('Pilih opsi pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                        <?php
                                                            $query    =mysqli_query($conn, "SELECT * FROM tbsatuan WHERE Status='0' ORDER BY idSatuan");
                                                            while ($data = mysqli_fetch_array($query)) {
                                                        ?>
                                                        <option value="<?=$data['idSatuan'];?>"><?php echo $data['idSatuan'].' - '.$data['Satuan'];?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success" name="pulihkanSatuan">Pulihkan</button>
                                                </div>
                                            </form>
                                            <script>
                                                $('#pulihkan').on('hidden.bs.modal', function () {
                                                    $(this).find('form').trigger('reset');
                                                })
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datatables -->
                                <div id="datatables">
                                    <table class="table table-stripped table-bordered table-hover" id="dtSatuan" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">ID Satuan</th>
                                                <th class="text-center">Satuan</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>                        
                                        <tbody>
                                            <?php
                                                $tampilDataSatuan= mysqli_query($conn,"SELECT * FROM tbsatuan WHERE Status='1'");
                                                while($data=mysqli_fetch_array($tampilDataSatuan)){
                                                    $idSatuan = $data['idSatuan'];
                                                    $Satuan = $data['Satuan'];
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?=$idSatuan;?></td>
                                                    <td><?php echo $Satuan;?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-warning btn-sm mb-4" data-toggle="modal" data-target="#ubah<?=$idSatuan;?>">
                                                            <i class="fas fa-edit">Ubah</i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm mb-4" data-toggle="modal" data-target="#nonaktif<?=$idSatuan;?>">
                                                            <i class="fas fa-trash">Nonaktif</i> 
                                                        </button>
                                                    </td>
                                                </tr>
                                            <!-- Modal Ubah -->
                                            <div class="modal fade" id="ubah<?=$idSatuan;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Ubah Data Satuan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="recipient-name" class="col-form-label">ID Satuan:</label>
                                                                        <input type="text" class="form-control" id="idSatuan" name="idSatuan" value="<?php echo $idSatuan;?>" readonly>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="message-text" class="col-form-label">Nama Satuan:</label>
                                                                        <input type="text" class="form-control" id="Satuan" name="Satuan" placeholder="Masukkan Nama Satuan" value="<?php echo $Satuan;?>" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idSatuan" value="<?=$idSatuan;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning" name="ubahSatuan">Ubah</button>
                                                            </div>
                                                        </form>
                                                        <script>
                                                            $('#ubah<?=$idSatuan;?>').on('hidden.bs.modal', function () {
                                                                $(this).find('form').trigger('reset');
                                                            })
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Nonaktif -->
                                            <div class="modal fade" id="nonaktif<?=$idSatuan;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                                <?php echo $idSatuan.' - '.$Satuan;?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idSatuan" value="<?=$idSatuan;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger" name="nonaktifSatuan">Nonaktifkan</button>
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
                        <a class="btn btn-primary" href="../../logout.php">Keluar</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skrip Datatables -->
        <script>
        $(document).ready(function() {
            $('#dtSatuan').DataTable( {
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