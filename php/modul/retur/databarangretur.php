<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';

    $_SESSION['tambah']='false';
    $_SESSION['gagal']='false';

    //Mengecek Jabatan yang Dapat Mengakses Halaman Retur
    if($_SESSION['Jabatan']=='Checker'){
        header('location:../../index.php');
    }

    // Proses Menambah Barang Retur, Ketika Data Barang Retur Ditambah
    if(isset($_POST['tambahBarangRetur'])){
        $idDetailMasuk=$_POST['idDetailMasuk'];
        $tglRetur= $_POST['tglRetur'];
        $Time = strtotime($_POST['tglRetur']);
        $query= mysqli_query($conn, "SELECT * FROM tbdetailmasuk INNER JOIN tbproduk ON tbdetailmasuk.idProduk=tbproduk.idProduk WHERE idDetailMasuk='$idDetailMasuk'");
        $row=mysqli_fetch_array($query);
        $idProduk=$row['idProduk'];
        $jumlahMasuk=$row['jumlahMasuk'];
        $jumlahRetur=$_POST['jumlahRetur'];
        $Alasan=$_POST['Alasan'];
        $imageRetur=$_FILES['imageRetur'];
        $Format=$imageRetur['type'];
        $Blob=addslashes(file_get_contents($imageRetur["tmp_name"]));
        $idRetur="R".$idDetailMasuk;
        $Status="1";

        $rowRetur= mysqli_query($conn,"SELECT * FROM tbretur
        INNER JOIN tbproduk ON tbretur.idProduk=tbproduk.idProduk
        INNER JOIN tbdetailmasuk ON tbretur.idDetailMasuk=tbdetailmasuk.idDetailMasuk
        WHERE idBarangRetur='$idRetur'");
        while($r=mysqli_fetch_array($rowRetur)){
            // Kueri mengubah data retur
            if($r['jumlahRetur']<=$r['stokProduk']){
                mysqli_query($conn,"INSERT INTO tbretur (idBarangRetur,idDetailMasuk,tanggalRetur,idProduk,jumlahRetur,Alasan,Bukti,Format,Status) VALUES ('$idRetur','$idDetailMasuk','$tglRetur','$idProduk','$jumlahRetur','$Alasan','$Blob','$Format','$Status')");
                mysqli_query($conn,"UPDATE tbproduk SET stokProduk=stokProduk-'".$r['jumlahRetur']."' WHERE idProduk='".$r['idProduk']."'");
                $_SESSION['confirm']='true';
            }else{
                $_SESSION['gagal']='true';
            } 
        }

        if($_SESSION['gagal']!='true'){
            $rowProduk=mysqli_query($conn, "SELECT * FROM tbproduk 
            INNER JOIN tbretur ON tbproduk.idProduk=tbretur.idProduk
            WHERE tbretur.idBarangRetur='$idRetur'");
    
            while($r2=mysqli_fetch_array($rowProduk)){
                mysqli_query($conn,"INSERT INTO tblog (idProduk,Tanggal,Keterangan,stokRetur,totalStok) VALUES ('".$r2['idProduk']."','".$r2['tanggalRetur']."','".$r2['Keterangan']."','".$r2['jumlahRetur']."','".$r2['stokProduk']."')");
                $_SESSION['terima']='true';
            }
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
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
                        <!-- Akhiran Topbar Navbar -->
                    </nav>
                    <!-- Akhiran Topbar -->

                    <!-- Page Content -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800">Tabel Data Barang Retur</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Data barang retur berhasil ditambah.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-secondary" role="alert">
                                    Data barang retur tidak terkoneksi.
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
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Barang Retur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Produk:</label>
                                                        <select class="form-control selectpicker" title="Pilih Produk"  data-live-search="true" id="exampleFormControlSelect1" id="idDetailMasuk" name="idDetailMasuk">
                                                        <?php
                                                            $query    =mysqli_query($conn, "SELECT * FROM tbdetailmasuk
                                                            INNER JOIN tbstokmasuk ON tbdetailmasuk.idBarangMasuk=tbstokmasuk.idBarangMasuk
                                                            INNER JOIN tbproduk ON tbdetailmasuk.idProduk=tbproduk.idProduk 
                                                            INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                            ORDER BY idDetailMasuk DESC");
                                                            while ($data = mysqli_fetch_array($query)) {
                                                            ?>
                                                            <option value="<?=$data['idDetailMasuk'];?>"><?php echo $data['tanggalMasuk'].' : '.$data['Produk'].' : '.$data['jumlahMasuk'].' '.$data['Satuan'];?></option>
                                                            <?php
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tanggal">Tanggal Retur:</label>
                                                        <input type="date" class="form-control" id="tglRetur" name="tglRetur" max="<?= date('Y-m-d'); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="label">Jumlah Retur:</label>
                                                        <input type="number" class="form-control" id="jumlahRetur" name="jumlahRetur" min="1" oninput="validity.valid||(value='');" placeholder="Masukkan Jumlah Retur" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">Alasan:</label>
                                                        <input type="text" class="form-control" id="Alasan" name="Alasan" placeholder="Masukkan Alasan">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="formFile" class="form-label">Upload Bukti:</label>
                                                        <input class="form-control" type="file" name="imageRetur" accept="image/png, image/jpg, image/jpeg" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary" name="tambahBarangRetur">Tambah</button>
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
                                    <table class="table table-stripped table-bordered table-hover" id="dtRetur" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">ID Barang Retur</th>
                                                <th class="text-center">Tanggal</th>
                                                <th class="text-center">Nama Produk</th>
                                                <th class="text-center">Jumlah Retur</th>
                                                <th class="text-center">Alasan</th>
                                                <th class="text-center">Bukti</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDataRetur= mysqli_query($conn,"SELECT *,tbretur.Status FROM tbretur 
                                                INNER JOIN tbdetailmasuk ON tbretur.idDetailMasuk=tbdetailmasuk.idDetailMasuk
                                                INNER JOIN tbproduk ON tbretur.idProduk=tbproduk.idProduk
                                                INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                ORDER BY idBarangRetur DESC;");
                                                while($data=mysqli_fetch_array($tampilDataRetur)){
                                                    $idBarangRetur = $data['idBarangRetur'];
                                                    $idDetailMasuk = $data['idDetailMasuk'];
                                                    $tanggalRetur= date('d-m-Y', strtotime($data['tanggalRetur']));
                                                    $Produk =$data['Produk'];
                                                    $jumlahRetur =$data['jumlahRetur'];
                                                    $Satuan=$data['Satuan'];
                                                    $Alasan =$data['Alasan'];
                                                    $Format =$data['Format'];
                                                    $Status =(int) $data['Status'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$idBarangRetur;?></td>
                                                <td class="text-center"><?=$tanggalRetur;?></td>
                                                <td><?=$Produk;?></td>
                                                <td class="text-center"><?=$jumlahRetur.' '.$Satuan;?></td>
                                                <td><?=$Alasan;?></td>
                                                <td class="text-center">
                                                    <a href="downloadbuktiretur.php?id=<?php echo $idBarangRetur;?>" target="_blank"><i class="fas fa-fw fa-download" style="color: #000000;"></i></a>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($Status == '1'){?>
                                                        <i class="fas fa-check">Disetujui</i>
                                                    <?php }else{?>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                            <?php
                                                };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>   
                            <!-- Akhiran Datatables -->
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
                        <a class="btn btn-danger" href="../../logout.php">Keluar</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skrip Datatables -->
        <script>
        $(document).ready(function() {
            $('#dtRetur').DataTable( {
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