<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';
	
    $_SESSION['tambah']='false';
    $_SESSION['ubah']='false';
    $_SESSION['hapus']='false';
    $_SESSION['confirm']='false';
    $_SESSION['cancel']='false';
    $_SESSION['gagal']='false';
    $_SESSION['over']='false';

    // Mengambil data idBarangKeluar
	$fetchIdBarangKeluar = $_GET['id'];
    $query= mysqli_query($conn, "SELECT * FROM tbstokkeluar WHERE idBarangKeluar='$fetchIdBarangKeluar'");
	$rowBarangKeluar = mysqli_fetch_array($query);

	$idBarangKeluar = $rowBarangKeluar['idBarangKeluar'];
	$tanggalKeluar = $rowBarangKeluar['tanggalKeluar'];
	$Keterangan = $rowBarangKeluar['Keterangan'];
	$Status = (int) $rowBarangKeluar['Status'];
		
    // Proses Menyimpan Perubahan Informasi Data Barang Keluar
    if(isset($_POST['simpanDataKeluar'])){
        $idBarangKeluar = $_POST['idBarangKeluar'];
        $tanggalKeluar = $_POST['tanggalKeluar'];
        $Keterangan = $_POST['Keterangan'];

        $simpanDataKeluar = mysqli_query($conn,"UPDATE tbstokkeluar SET tanggalKeluar='$tanggalKeluar',Keterangan='$Keterangan' WHERE idBarangKeluar='$idBarangKeluar'");
        
        // Kueri mengubah data detail barang masuk
        if($simpanDataKeluar){
            $_SESSION['ubah']='true'; 
        }else{
            $_SESSION['gagal']='true';   
        } 
    }

    // Proses Menambah Detail Produk Barang Keluar, Ketika Data Detail Barang Keluar Ditambah
    if(isset($_POST['tambahDetailProdukKeluar'])){
        $idBarangKeluar= $_POST['idBarangKeluar'];
        $idProduk= $_POST['idProduk'];
        $jumlahKeluar= $_POST['jumlahKeluar'];
        $cekIDKeluar =mysqli_query($conn, "SELECT * FROM tbdetailkeluar WHERE idBarangKeluar='$idBarangKeluar'");

        $queryProduk= mysqli_query($conn, "SELECT * FROM tbproduk WHERE idProduk='$idProduk'");
	    $rowProduk = mysqli_fetch_array($queryProduk);
        $stokProduk = $rowProduk['stokProduk'];
	    $hargaJual = $rowProduk['hargaJual'];

        $noUrut=mysqli_num_rows($cekIDKeluar);

        // Mengecek Nomor Urut ID Keluar
        if($noUrut>0){
            $nextNoUrut = $noUrut + 1;
            $Jumlah=sprintf("%02s",$nextNoUrut);
        }else{
            $Jumlah="01";
        }
        $idDetailKeluar=$idBarangKeluar."-".$Jumlah;
        
        if($jumlahKeluar<=$stokProduk){
            mysqli_query($conn,"INSERT INTO tbdetailkeluar(idDetailKeluar,idBarangKeluar,idProduk,hargaKeluar,jumlahKeluar) VALUES ('$idDetailKeluar','$idBarangKeluar','$idProduk','$hargaJual','$jumlahKeluar')");
            $_SESSION['tambah']='true';
        }else{
            $_SESSION['over']='true';
        }
    }

    // Proses Mengubah Detail Produk Barang Keluar, Ketika Data Detail Barang Keluar Diubah
    if(isset($_POST['ubahDetailProdukKeluar'])){
        $idBarangKeluar= $_POST['idBarangKeluar'];
        $idDetailKeluar= $_POST['idDetailKeluar'];

        $idProduk=$_POST['idProduk'];
        $jumlahKeluar= $_POST['jumlahKeluar'];

        $queryProduk= mysqli_query($conn, "SELECT * FROM tbproduk WHERE idProduk='$idProduk'");
	    $rowProduk = mysqli_fetch_array($queryProduk);
        $stokProduk = $rowProduk['stokProduk'];
	    $hargaJual = $rowProduk['hargaJual'];

        if($jumlahKeluar<=$stokProduk){
            mysqli_query($conn,"UPDATE tbdetailkeluar SET idProduk='$idProduk',hargaKeluar='$hargaJual',jumlahKeluar='$jumlahKeluar' WHERE idDetailKeluar='$idDetailKeluar'");
            $_SESSION['ubah']='true';
        }else{
            $_SESSION['over']='true';
        } 
    }

    // Proses Menghapus Detail Produk Barang Keluar, Ketika Data Detail Barang Keluar Dihapus
    if(isset($_POST['hapusDetailProdukKeluar'])){
        $idBarangKeluar= $_POST['idBarangKeluar'];
        $idDetailKeluar= $_POST['idDetailKeluar'];
        $hapusDetailProdukKeluar = mysqli_query($conn,"DELETE FROM tbdetailkeluar WHERE idDetailKeluar='$idDetailKeluar'");
        
        // Kueri menghapus data detail barang keluar
        if($hapusDetailProdukKeluar){
            $_SESSION['hapus']='true';
        }else{
            $_SESSION['gagal']='true';
        } 
    }

    // Proses Menghapus Data Barang Keluar, Ketika Data Barang Keluar Dicancel
    if(isset($_POST['hapusBarangKeluar'])){
        $idBarangKeluar= $_POST['idBarangKeluar'];
        $hapusDetailKeluar =mysqli_query($conn,"DELETE FROM tbdetailkeluar WHERE idBarangKeluar='$idBarangKeluar'");
        $hapusProdukKeluar = mysqli_query($conn,"DELETE FROM tbstokkeluar WHERE idBarangKeluar='$idBarangKeluar'");
        
        // Kueri menghapus data barang keluar dan data detail barang keluar
        if($hapusProdukKeluar OR $hapusDetailKeluar){
            echo '<script type="text/javascript">window.location.href = "databarangkeluar.php";</script>';
            $_SESSION['cancel']='true';
        }else{
            $_SESSION['gagal']='true';
        } 
    }

    // Proses Mengurangi Stok Data Produk dan Mengubah Status Data Barang Keluar, Ketika Data Barang Keluar Diconfirm
    if(isset($_POST['confirmBarangKeluar'])){
        $idBarangKeluar= $_POST['idBarangKeluar'];
        $rowDetailKeluar= mysqli_query($conn,"SELECT * FROM tbdetailkeluar 
        LEFT JOIN tbproduk ON tbdetailkeluar.idProduk=tbproduk.idProduk
        WHERE idBarangKeluar='$idBarangKeluar'");
        while($r=mysqli_fetch_array($rowDetailKeluar)){
            // Kueri mengubah data detail barang keluar dan data barang keluar
            if($r['jumlahKeluar']<=$r['stokProduk']){
                $queryUpdate=mysqli_query($conn,"UPDATE tbstokkeluar SET Status='1' WHERE idBarangKeluar='$idBarangKeluar'");
                if($queryUpdate){
                    mysqli_query($conn,"UPDATE tbproduk SET stokProduk=stokProduk-'".$r['jumlahKeluar']."' WHERE idProduk='".$r['idProduk']."'");
                    $_SESSION['confirm']='true';
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
            LEFT JOIN tbdetailkeluar ON tbproduk.idProduk=tbdetailkeluar.idProduk
            LEFT JOIN tbstokkeluar ON tbdetailkeluar.idBarangKeluar=tbstokkeluar.idBarangKeluar 
            WHERE tbstokkeluar.idBarangKeluar='$idBarangKeluar'");
            while($r2=mysqli_fetch_array($rowProduk)){
                $keteranganKeluar="Barang Keluar (".$r2['Keterangan'].")";
                mysqli_query($conn,"INSERT INTO tblog (idProduk,Tanggal,Keterangan,stokKeluar,totalStok) VALUES ('".$r2['idProduk']."','".$r2['tanggalKeluar']."','".$keteranganKeluar."','".$r2['jumlahKeluar']."','".$r2['stokProduk']."')");
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
    <!-- Akhiran Head HTML -->

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
                        <span>Dashboard</span>
                    </a>
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
                            <a class="collapse-item" href="databarangkeluar.php"><i class="fas fa-fw fa-arrow-up"></i>Barang Keluar</a>
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
                        <h1 class="h3 mb-2 text-gray-800">Data Detail Barang Keluar</h1>
                        <?php
                            if($_SESSION['tambah']=='true'){
                                echo '<div class="alert alert-primary" role="alert">
                                    Produk berhasil ditambah pada data detail barang keluar.
                                </div>';
                            }else if($_SESSION['ubah']=='true'){
                                echo '<div class="alert alert-warning" role="alert">
                                    Produk berhasil diubah pada data detail barang keluar.
                                </div>';
                            }else if($_SESSION['hapus']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Produk berhasil dihapus pada data detail barang keluar.
                                </div>';
                            }else if($_SESSION['confirm']=='true'){
                                echo '<div class="alert alert-success" role="alert">
                                    Data barang keluar berhasil diconfirm.
                                </div>';
                            }else if($_SESSION['over']=='true'){
                                echo '<div class="alert alert-secondary" role="alert">
                                    Jumlah masukan produk yang keluar melebihi stok produk.
                                </div>';
                            }else if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-secondary" role="alert">
                                    Data barang keluar tidak terkoneksi.
                                </div>';
                            }
                        ?>      
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">ID Barang Keluar:</label>
                                        <input type="text" class="form-control" id="idBarangKeluar" name="idBarangKeluar" value="<?php echo $idBarangKeluar;?>" readonly>
                                    </div>
                                    <?php if($Status == '0'){?>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Tanggal Keluar:</label>
                                        <input type="date" class="form-control" id="tanggalKeluar" name="tanggalKeluar" value="<?php echo $tanggalKeluar;?>" max="<?= date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Keterangan:</label>
                                        <input type="text" class="form-control" id="Keterangan" name="Keterangan" value="<?php echo $Keterangan;?>" required>
                                    </div>
                                    <?php }else{?>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Tanggal Keluar:</label>
                                        <input type="date" class="form-control" id="tanggalKeluar" name="tanggalKeluar" value="<?php echo $tanggalKeluar;?>" max="<?= date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Keterangan:</label>
                                        <input type="text" class="form-control" id="Keterangan" name="Keterangan" value="<?php echo $Keterangan;?>" readonly>
                                    </div>
                                    <?php }?>
                                    <?php if($Status == 1){?>
                                        <a href="cetakstrukkeluar.php?id=<?php echo $idBarangKeluar;?>" target="_blank" class="btn btn-primary" role="button"><i class="fas fa-fw fa-download" style="color: #000000;width:100px;color:white;">Cetak bon</i></a>
                                    <?php }else{?>
                                        <button type="submit" class="btn btn-success" name="simpanDataKeluar">
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
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Detail Barang Keluar</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Produk:</label>
                                                        <select class="form-control selectpicker" title="Pilih Produk"  data-live-search="true" id="exampleFormControlSelect1" id="idProduk" name="idProduk">
                                                            <?php
                                                                $query    =mysqli_query($conn, "SELECT * FROM tbproduk WHERE tbproduk.idProduk NOT IN (SELECT DISTINCT tbdetailkeluar.idProduk FROM tbdetailkeluar WHERE tbdetailkeluar.idBarangKeluar = '$fetchIdBarangKeluar') ORDER BY tbproduk.Produk ");       
                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                                                <option value="<?=$data['idProduk'];?>"><?php echo $data['Produk'].' ('."Rp " . number_format($data['hargaJual'],2,',','.').')'.' - Stok: '.$data['stokProduk'];?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="col-form-label">Jumlah Keluar:</label>
                                                        <input type="number" class="form-control" id="jumlahKeluar" name="jumlahKeluar" min="1" placeholder="Masukkan Jumlah Keluar" oninput="validity.valid||(value='');">
                                                    </div> 
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idBarangKeluar" value="<?=$idBarangKeluar;?>">
                                                        <input type="hidden" name="idDetailKeluar" value="<?=$idDetailKeluar;?>">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary" name="tambahDetailProdukKeluar">Tambah</button>
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
                                <!-- Modal Tambah -->

                                <!-- Datatables -->
                                <div id="datatables">
                                    <table class="table table-stripped table-bordered table-hover" id="dtDetailKeluar" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Nama Produk</th>
                                                <th class="text-center">Harga Item</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-center">Total</th>
                                                <?php if($Status==0){?>
                                                    <th class="text-center">Aksi</th>
                                                <?php }?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tampilDetailProdukKeluar= mysqli_query($conn,"
                                                SELECT *,tbstokkeluar.Status FROM tbdetailkeluar
                                                INNER JOIN tbstokkeluar ON tbdetailkeluar.idBarangKeluar=tbstokkeluar.idBarangKeluar
                                                INNER JOIN tbproduk ON tbdetailkeluar.idProduk=tbproduk.idProduk
                                                INNER JOIN tbsatuan ON tbsatuan.idSatuan=tbproduk.idSatuan
                                                WHERE tbdetailkeluar.idBarangKeluar='$fetchIdBarangKeluar';");
                                                $inc=1;
                                                while($data=mysqli_fetch_array($tampilDetailProdukKeluar)){
                                                    $idDetailKeluar = $data['idDetailKeluar'];
                                                    $idBarangKeluar= $data['idBarangKeluar'];
                                                    $idProduk =$data['idProduk'];
                                                    $Produk =$data['Produk'];
                                                    $stokProduk =$data['stokProduk'];
                                                    $Satuan =$data['Satuan'];
                                                    $hargaKeluar=$data['hargaKeluar'];
                                                    $konversiHargaKeluar = "Rp " . number_format($hargaKeluar,2,',','.');
                                                    $jumlahKeluar =$data['jumlahKeluar'];
                                                    $hargaTotalHargaKeluar=$hargaKeluar*$jumlahKeluar;
                                                    $konversiTotalHargaKeluar = "Rp " . number_format($hargaTotalHargaKeluar,2,',','.');
                                                    $Status =(int) $data['Status'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$inc++;?></td>
                                                <td><?=$Produk;?></td>
                                                <td class="text-center"><?=$konversiHargaKeluar;?></td>
                                                <td class="text-center"><?=$jumlahKeluar.' '.$Satuan;?></td>
                                                <td class="text-center"><?=$konversiTotalHargaKeluar;?></td>
                                                <?php if($Status==0){?>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-warning btn-sm mb-4" data-toggle="modal" data-target="#ubah<?=$idDetailKeluar;?>">
                                                            <i class="fas fa-edit">Ubah</i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm mb-4" data-toggle="modal" data-target="#hapus<?=$idDetailKeluar;?>">
                                                            <i class="fas fa-trash">Hapus</i> 
                                                        </button>
                                                    </td>
                                                <?php }?>
                                            </tr>

                                            <!-- Modal Ubah -->
                                            <div class="modal fade" id="ubah<?=$idDetailKeluar;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Ubah Jumlah Produk Keluar</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="message-text" class="col-form-label">Produk:</label>
                                                                    <select class="form-control" id="idProduk" name="idProduk">
                                                                        <option value="<?php echo $idProduk;?>" hidden><?php echo $Produk.' ('."Rp " . number_format($data['hargaKeluar'],2,',','.').') - Stok: '.$stokProduk;?></option>
                                                                        <?php
                                                                            $query    =mysqli_query($conn, "SELECT * FROM tbproduk ORDER BY Produk");
                                                                            while ($data = mysqli_fetch_array($query)) {
                                                                            ?>
                                                                            <option value="<?=$data['idProduk'];?>"><?php echo $data['Produk'].' ('."Rp " . number_format($data['hargaJual'],2,',','.'). ') - Stok: '.$data['stokProduk'];?></option>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Jumlah Keluar:</label>
                                                                    <input type="number" class="form-control" id="jumlahKeluar" name="jumlahKeluar" min="0" value="<?php echo $jumlahKeluar;?>" oninput="validity.valid||(value='');">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idBarangKeluar" value="<?=$idBarangKeluar;?>">
                                                                <input type="hidden" name="idDetailKeluar" value="<?=$idDetailKeluar;?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning" name="ubahDetailProdukKeluar">Ubah</button>
                                                            </div>
                                                        </form>
														<script>
															$('#ubah<?=$idDetailKeluar;?>').on('hidden.bs.modal', function () {
																$(this).find('form').trigger('reset');
															})
														</script>                
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Modal Ubah -->

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="hapus<?=$idDetailKeluar;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                                    <?php echo $Produk.' : '.$jumlahKeluar.' '.$Satuan;?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idBarangKeluar" value="<?=$idBarangKeluar;?>">
                                                                    <input type="hidden" name="idDetailKeluar" value="<?=$idDetailKeluar;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="hapusDetailProdukKeluar">Hapus</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
                                            <!-- /Modal Hapus -->
                                            <?php
                                                };
                                            ?>

                                            <!-- Modal Cancel -->
                                            <div class="modal fade" id="cancel<?=$idBarangKeluar;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin untuk menghapus data barang keluar tersebut?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idBarangKeluar" value="<?=$idBarangKeluar;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger" name="hapusBarangKeluar">Hapus</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
                                            <!-- /Modal Cancel -->

                                            <!-- Modal Confirm -->
                                            <div class="modal fade" id="confirm<?=$idBarangKeluar;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin untuk mengconfirm permintaan barang keluar tersebut?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idBarangKeluar" value="<?=$idBarangKeluar;?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success" name="confirmBarangKeluar">Confirm</button>
                                                                </div>
                                                            </div>
                                                    </form>   
                                                </div>
                                            </div>
                                            <!-- /Modal Confirm -->
                                        </tbody>
                                    </table>
                                    <?php 
									if($Status == 1){?>
                                    <?php }else{?>
                                            <?php 
                                                $jabatan=$_SESSION['Jabatan']=='Owner';
                                                $jabatan2=$_SESSION['Jabatan']=='Checker';
                                                if($jabatan OR $jabatan2){
                                            ?>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirm<?=$idBarangKeluar;?>"><i class="fas fa-check">Confirm</i></button>
                                                <button class="btn btn-danger ml-2" type="button" data-toggle="modal" data-target="#cancel<?=$idBarangKeluar;?>"><i class="fas fa-times">Cancel</i></button>
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
            $('#dtDetailKeluar').DataTable( {
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
    <!-- Akhiran Body HTML -->
</html>