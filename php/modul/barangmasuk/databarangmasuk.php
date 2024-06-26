<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';

    $_SESSION['tambah']='false';
    $_SESSION['terima']='false';
    $_SESSION['tolak']='false';

    if ($_SESSION['Jabatan'] == 'Penjaga Toko') {
        header('location:../../index.php');
    }
		
    // Proses Menambah Barang Masuk, Ketika Data Barang Masuk Ditambah
    if(isset($_POST['tambahBarangMasuk'])){
        $tglMasuk= $_POST['tglMasuk'];
        $Time = strtotime($_POST['tglMasuk']);
        $idPemasok= $_POST['idPemasok'];
        $imageMasuk=$_FILES['imageMasuk'];
        $Format=$imageMasuk['type'];
        $Blob=addslashes(file_get_contents($imageMasuk["tmp_name"]));
        $Penerima= $_POST['Penerima'];
        
        // Mengambil ID Barang Masuk Maksimal
        $cekMaks = "SELECT MAX(idBarangMasuk) AS max_id FROM tbstokmasuk";
        $Hasil = mysqli_query($conn,$cekMaks);
        $row = mysqli_fetch_assoc($Hasil);
        $maxIdBarangMasuk = $row["max_id"];

        // Mengecek ID Barang Masuk Max ada atau tidak
        if($maxIdBarangMasuk) {
            $noUrut = substr($maxIdBarangMasuk, strlen('M'));
            $noUrut++;
            $noUrutBaru = 'M' . str_pad($noUrut, 4, '0', STR_PAD_LEFT);
        } else {
            $noUrutBaru = 'M0001';
        }

        $idMasuk=$noUrutBaru;
        $tambahBarangMasuk = mysqli_query($conn,"INSERT INTO tbstokmasuk (idBarangMasuk,tanggalMasuk,idPemasok,Bukti,Format,idPengguna,Status) VALUES ('$idMasuk','$tglMasuk','$idPemasok','$Blob','$Format','$Penerima','0')");

        // Kueri menambah data barang masuk
        if($tambahBarangMasuk){
            $_SESSION['tambah']='true';
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
                            <!-- Nav Item - Informasi Pengguna -->
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
                        <h1 class="h3 mb-2 text-gray-800">Tabel Data Barang Masuk</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data barang masuk berhasil ditambah.
                                </div>';
                            }else if($_SESSION['tolak']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data barang masuk berhasil dihapus.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data barang masuk tidak terkoneksi.
                                </div>';
                            }
                        ?>      
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">
                                    <i class="fas fa-plus">Tambah</i>
                                </button>
                                <br><br>
                                <!-- Modal Tambah -->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Barang Masuk</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="tanggal">Tanggal Masuk: </label>
                                                        <input type="date" class="form-control" id="tglMasuk" name="tglMasuk" max="<?= date('Y-m-d'); ?>" oninvalid="this.setCustomValidity('Pilih tanggal pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Pemasok: </label>
                                                        <select class="form-control selectpicker" title="Pilih Pemasok" id="exampleFormControlSelect1"  data-live-search="true" name="idPemasok" oninvalid="this.setCustomValidity('Pilih opsi pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
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
                                                        <label for="formFile" class="form-label">Upload Bukti:</label>
                                                        <input class="form-control" type="file" name="imageMasuk" accept="image/png, image/jpg, image/jpeg" oninvalid="this.setCustomValidity('Masukkan file lampiran pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Penerima: </label>
                                                            <select class="form-control selectpicker" title="Pilih Penerima"  data-live-search="true" id="exampleFormControlSelect1" id="Penerima" name="Penerima" oninvalid="this.setCustomValidity('Pilih opsi pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                                            <?php
                                                                $query    =mysqli_query($conn, "SELECT * FROM tbpengguna ORDER BY Nama");
                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                                                <option value="<?=$data['idPengguna'];?>"><?php echo $data['Nama'];?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                            </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary" name="tambahBarangMasuk">Tambah</button>
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
                                        <table class="table table-stripped table-bordered table-hover" id="dtStokMasuk" width="100%" cellspacing="0">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-center">ID Barang Masuk</th>
                                                    <th class="text-center">Tanggal</th>
                                                    <th class="text-center">Pemasok</th>
                                                    <th class="text-center">Jumlah Item</th>
                                                    <th class="text-center">Bukti</th>
                                                    <th class="text-center">Penerima</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $tampilDataProdukMasuk= mysqli_query($conn,"
                                                    SELECT * FROM tbstokmasuk
                                                    INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
                                                    INNER JOIN tbpengguna ON tbstokmasuk.idPengguna=tbpengguna.idPengguna
                                                    ORDER BY idBarangMasuk
													DESC;");
                                                    $inc=1;
                                                    while($data=mysqli_fetch_array($tampilDataProdukMasuk)){
                                                        $idBarangMasuk = $data['idBarangMasuk'];
                                                        $tanggalMasuk= date('d-m-Y', strtotime($data['tanggalMasuk']));
                                                        $Pemasok =$data['Pemasok'];
                                                        $hitungJumlah= mysqli_query($conn,"SELECT * FROM tbdetailmasuk WHERE idBarangMasuk='$idBarangMasuk' GROUP BY idProduk;");
                                                        $jumlahItem = mysqli_num_rows($hitungJumlah);
                                                        $Bukti =$data['Bukti'];
                                                        $Format =$data['Format'];
                                                        $Penerima=$data['Nama'];
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?=$idBarangMasuk;?></td>
                                                    <td class="text-center"><?=$tanggalMasuk;?></td>
                                                    <td><?=$Pemasok;?></td>
                                                    <td class="text-center"><?=$jumlahItem;?></td>
                                                    <td class="text-center">
                                                        <a href="downloadbuktimasuk.php?id=<?php echo $idBarangMasuk;?>" target="_blank"><i class="fas fa-fw fa-download" style="color: #000000;"></i></a>
                                                    </td>
                                                    <td class="text-center"><?=$Penerima;?></td>
                                                    <td class="text-center">
                                                        <a href="detailbarangmasuk.php?id=<?php echo $idBarangMasuk;?>"><i class="fas fa-fw fa-list" style="color: #000000;"></i></a>
                                                    </td>
                                                </tr>
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
                    <!-- Akhiran Page Content -->

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
                <!-- Akhiran Main Content -->
            </div>

            <script>
                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                }
            </script>
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
        var tanggal=document.getElementById('tglMasuk').value;
        
        $(document).ready(function() {
            $('#dtStokMasuk').DataTable( {
                lengthMenu: [25, 50, 100, 200, 500],
                scrollX:true,
				order: []
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