<?php
    require '../../fungsi.php';
    require '../../ceklogin.php';
	
    $_SESSION['nonaktif']='false';
    $_SESSION['ubah']='false';

    // Mengambil data idProduk
	$fetchIdProduk = $_GET['id'];
    $query = mysqli_query($conn,"SELECT * FROM tbproduk
    INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
    WHERE idProduk='$fetchIdProduk'");

    $rowProduk = mysqli_fetch_array($query);

	$idProduk=$rowProduk['idProduk'];
    $Produk=$rowProduk['Produk'];
    $idSatuan=$rowProduk['idSatuan'];
    $Satuan=$rowProduk['Satuan'];
    $hargaJual=$rowProduk['hargaJual'];
    $konversiHargaJual = "Rp " . number_format($hargaJual,2,',','.');
    $stokProduk=$rowProduk['stokProduk'];
    $Status=(bool) $rowProduk['Status'];
		
    // Proses Menyimpan Data Produk, Ketika Data Produk Disimpan
    if(isset($_POST['simpanProduk'])){
        $idProduk= $_POST['idProduk'];
        $Produk= $_POST['Produk'];
        $idSatuan= $_POST['idSatuan'];
        $stokProduk= $_POST['stokProduk'];
        $hargaJual= $_POST['hargaJual'];
        $ubahProduk = mysqli_query($conn,"UPDATE tbproduk SET Produk='$Produk',idSatuan='$idSatuan',hargaJual='$hargaJual',stokProduk='$stokProduk' WHERE idProduk='$idProduk'");
        
        // Kueri mengubah data satuan
        if($ubahProduk){
            echo '<script type="text/javascript">window.location.href = "produk.php";</script>';
            $_SESSION['ubah']='true';
        }else{
            $_SESSION['gagal']='true'; 
        }
    }
		
    // Proses Menonaktifkan Data Produk, Ketika Data Produk Dinonaktif
    if(isset($_POST['nonaktifProduk'])){
        $idProduk= $_POST['idProduk'];
        $nonaktifProduk= mysqli_query($conn,"UPDATE tbproduk SET Status='0' WHERE idProduk='$idProduk'");
        
        // Kueri mengubah status data produk ke '0'
        if($nonaktifProduk){
            echo '<script type="text/javascript">window.location.href = "produk.php";</script>';
            $_SESSION['nonaktif']='true';
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
                        <h1 class="h3 mb-2 text-gray-800">Data Detail Produk</h1>
                        <?php
                             if($_SESSION['gagal']=='true'){
                                echo '<div class="alert alert-danger" role="alert">
                                    Data produk tidak terkoneksi.
                                </div>';
                            }
                        ?>  
                        <form method="POST">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">ID Produk:</label>
                                        <input type="text" class="form-control" id="idProduk" name="idProduk" value="<?php echo $idProduk;?>" readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="recipient-name" class="col-form-label">Nama Produk:</label>
                                                <input type="text" class="form-control" id="Produk" name="Produk" value="<?php echo $Produk;?>" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="message-text" class="col-form-label">Satuan:</label>
                                                <select class="form-control" title="Pilih Satuan" id="idSatuan" name="idSatuan">
                                                    <?php
                                                        $querySelect=mysqli_query($conn, "SELECT * FROM tbproduk
                                                        INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
                                                        WHERE idProduk='$fetchIdProduk'");
                                                        $dataSelect=mysqli_fetch_array($querySelect);
                                                    ?>
                                                    <option value="<?php echo $dataSelect['idSatuan'];?>" hidden><?php echo $dataSelect['Satuan'];?></option>
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
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Harga Jual per Unit:</label>
                                        <input type="number" class="form-control" id="hargaJual" name="hargaJual" min="0" oninvalid="this.setCustomValidity('Masukkan harga jual per unit pada kolom pengisian ini!')" onchange="this.setCustomValidity('')" value="<?php echo $hargaJual;?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Jumlah Stok:</label>
                                        <input type="number" class="form-control" id="stokProduk" name="stokProduk" value="<?php echo $stokProduk;?>" readonly>
                                    </div>
                                    <div class="float-left">
										<?php 
											$jabatan=$_SESSION['Jabatan']=='Owner';
											if($jabatan){
										?>
                                        <button type="submit" class="btn btn-primary" id="simpanProduk" name="simpanProduk">
                                            <i class="fas fa-edit">Simpan</i>
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#nonaktif<?=$idProduk;?>">
                                            <i class="fas fa-trash">Nonaktifkan</i> 
                                        </button>
										<?php 
											}
										?>
                                        <a class="btn btn-secondary" href="../laporan/cetaklaporanpersediaanproduk.php?id=<?php echo $idProduk;?>" target="_blank">
                                            <i class="fas fa-print">Cetak</i>
                                        </a>
                                    </div>
                                </div>
                        </form>
                        <!-- Modal Nonaktif -->
                        <div class="modal fade" id="nonaktif<?=$idProduk;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <form method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Apakah anda yakin ingin menonaktifkan data produk ini?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <b><?php echo 'Nama Produk: '.$Produk;?></b>
                                            <br><?php echo 'Harga Jual per Unit: '.$konversiHargaJual;?>
                                            <br><?php echo 'Jumlah Stok: '.$stokProduk.' '.$Satuan;?>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="idProduk" value="<?=$idProduk;?>">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger" name="nonaktifProduk">Nonaktifkan</button>
                                        </div>
                                    </div>
                                </form>   
                            </div>
                        </div>   
                        <div class="card-body">
                            <!-- Datatables -->
                            <div id="datatables">
                                <table class="table table-stripped table-bordered table-hover" id="dtLaporanMasuk" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2" class="text-center">Tanggal</th>
                                            <th rowspan="2" class="text-center">Dari/Untuk</th>
                                            <th colspan="3" class="text-center">Pembelian</th>
                                            <th colspan="3" class="text-center">Harga Pokok Penjualan</th>
                                            <th colspan="3" class="text-center">Persediaan</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $tampilDetailProdukMasuk=mysqli_query($conn,"SELECT tbstokmasuk.tanggalMasuk, tbpemasok.Pemasok, tbdetailmasuk.jumlahMasuk, tbdetailmasuk.totalHargaMasuk FROM tbdetailmasuk
                                            INNER JOIN tbstokmasuk ON tbdetailmasuk.idBarangMasuk=tbstokmasuk.idBarangMasuk
                                            INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
                                            WHERE tbdetailmasuk.idProduk='$idProduk'");
                                            $totalUnit=0;
                                            $hargaUnit=0;
                                            $totalHarga=0;
                                            while($data=mysqli_fetch_array($tampilDetailProdukMasuk)){
                                                $tanggalMasuk = $data['tanggalMasuk'];
                                                $jumlahMasuk =$data['jumlahMasuk'];
                                                $Pemasok=$data['Pemasok'];
                                                $totalHargaMasuk =$data['totalHargaMasuk'];
                                                $hargaMasukPerItem=$totalHargaMasuk/$jumlahMasuk;
                                                $konversiHargaMasukPerItem= "Rp " . number_format($hargaMasukPerItem,2,',','.');
                                                $konversiTotalHargaMasuk= "Rp " . number_format($totalHargaMasuk,2,',','.');
                                                $totalUnit=$totalUnit+$jumlahMasuk;
                                                $hargaUnit=$hargaMasukPerItem;
                                                $totalHarga=$totalHarga+$totalHargaMasuk;
                                                $konversiHargaUnit= "Rp " . number_format($hargaUnit,2,',','.');
                                                $konversiTotalHarga= "Rp " . number_format($totalHarga,2,',','.');
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$tanggalMasuk;?></td>
                                                <td class="text-left"><?=$Pemasok;?></td>

                                                <td class="text-center"><?=$jumlahMasuk;?></td>
                                                <td class="text-center"><?=$konversiHargaMasukPerItem;?></td>
                                                <td class="text-center"><?=$konversiTotalHargaMasuk;?></td>
                                                
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>

                                                <td class="text-center"><?=$totalUnit;?></td>
                                                <td class="text-center"><?=$konversiHargaUnit;?></td>
                                                <td class="text-center"><?=$konversiTotalHarga;?></td>
                                            </tr>
                                        <?php }?>
                                        <?php
                                            $tampilDetailProdukKeluar=mysqli_query($conn,"SELECT tbstokkeluar.tanggalKeluar, tbstokkeluar.Keterangan, tbdetailkeluar.jumlahKeluar, tbdetailkeluar.totalHargaKeluar FROM tbdetailkeluar
                                            INNER JOIN tbstokkeluar ON tbdetailkeluar.idBarangKeluar=tbstokkeluar.idBarangKeluar
                                            WHERE tbdetailkeluar.idProduk='$idProduk' AND tbstokkeluar.Status='1'
                                            UNION
                                            SELECT tbretur.tanggalRetur, tbretur.Alasan, tbretur.jumlahRetur, tbretur.totalHargaRetur FROM tbretur
                                            WHERE tbretur.idProduk='$idProduk' AND tbretur.Status='1'");
                                            $inc=1;
                                            while($data=mysqli_fetch_array($tampilDetailProdukKeluar)){
                                                $tanggalKeluar = $data['tanggalKeluar'];
                                                $jumlahKeluar=$data['jumlahKeluar'];
                                                $Keterangan=$data['Keterangan'];
                                                $totalHargaKeluar =$data['totalHargaKeluar'];
                                                $hargaKeluarPerItem=$totalHargaKeluar/$jumlahKeluar;
                                                $konversiHargaKeluarPerItem= "Rp " . number_format($hargaKeluarPerItem,2,',','.');
                                                $konversiTotalHargaKeluar= "Rp " . number_format($totalHargaKeluar,2,',','.');
                                                $totalUnit=$totalUnit-$jumlahKeluar;
                                                $hargaUnit=$hargaKeluarPerItem;
                                                $totalHarga=$totalHarga-$totalHargaKeluar;
                                                $konversiHargaUnit= "Rp " . number_format($hargaUnit,2,',','.');
                                                $konversiTotalHarga= "Rp " . number_format($totalHarga,2,',','.');
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$tanggalKeluar;?></td>
                                                <td class="text-right"><?=$Keterangan;?></td>

                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                
                                                <td class="text-center"><?=$jumlahKeluar;?></td>
                                                <td class="text-center"><?=$konversiHargaKeluarPerItem;?></td>
                                                <td class="text-center"><?=$konversiTotalHargaKeluar;?></td>

                                                <td class="text-center"><?=$totalUnit;?></td>
                                                <td class="text-center"><?=$konversiHargaUnit;?></td>
                                                <td class="text-center"><?=$konversiTotalHarga;?></td>
                                            </tr>
                                        <?php }?>
                                        <?php
                                            $tampilDetailOpname=mysqli_query($conn,"SELECT tbopname.tanggalOpname, tbopname.Keterangan, tbdetailopname.jumlahSistem,tbdetailopname.jumlahFisik, tbdetailopname.totalHargaPenyusutan FROM tbdetailopname
                                            INNER JOIN tbopname ON tbdetailopname.idOpname=tbopname.idOpname
                                            WHERE tbdetailopname.idProduk='$idProduk' AND tbopname.Status='1'");
                                            $inc=1;
                                            while($data=mysqli_fetch_array($tampilDetailOpname)){
                                                $tanggalOpname = $data['tanggalOpname'];
                                                $Keterangan=$data['Keterangan'];
                                                $jumlahOpname=$data['jumlahFisik'];
                                                $jumlahSistem=$data['jumlahSistem'];

                                                $Selisih=-($jumlahOpname-$jumlahSistem);
                                                $totalHargaPenyusutan =-($data['totalHargaPenyusutan']);
                                                $hargaPenyusutanPerItem=$totalHargaPenyusutan/$Selisih;

                                                $konversiHargaPenyusutanPerItem= "Rp " . number_format($hargaPenyusutanPerItem,2,',','.');
                                                $konversiTotalHargaPenyusutan= "Rp " . number_format($totalHargaPenyusutan,2,',','.');

                                                $totalHarga=$totalHarga-$totalHargaPenyusutan;
                                                $hargaUnit=$totalHarga/$jumlahOpname;
                                                $konversiHargaUnit= "Rp " . number_format($hargaUnit,2,',','.');
                                                $konversiTotalHarga= "Rp " . number_format($totalHarga,2,',','.');
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$tanggalOpname;?></td>
                                                <td class="text-right"><?=$Keterangan;?></td>

                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                
                                                <td class="text-center"><?=$Selisih;?></td>
                                                <td class="text-center"><?=$konversiHargaPenyusutanPerItem;?></td>
                                                <td class="text-center"><?=$konversiTotalHargaPenyusutan;?></td>

                                                <td class="text-center"><?=$jumlahOpname;?></td>
                                                <td class="text-center"><?=$konversiHargaUnit;?></td>
                                                <td class="text-center"><?=$konversiTotalHarga;?></td>
                                            </tr>
                                        <?php }?>
                                    </tbody>  
                                </table>
                            </div>
                            <!-- /Datatables --> 
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