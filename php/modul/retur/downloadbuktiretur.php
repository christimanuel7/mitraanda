<?php
    require '../../fungsi.php';

	if ($_SESSION['Jabatan'] == 'Checker') {
        header('location:../../index.php');
    }
	
	$idRetur = $_GET['id'];
	
	$sql="SELECT * FROM tbretur
	INNER JOIN tbproduk ON tbretur.idProduk=tbproduk.idProduk
	INNER JOIN tbdetailmasuk ON tbretur.idDetailMasuk=tbdetailmasuk.idDetailMasuk
	WHERE idBarangRetur = '$idRetur'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 0) {
		die('File bukti tidak ada.');
	}

	$row=mysqli_fetch_object($result);
    header("Content-type: ".$row->Format);
    echo $row->Bukti;
?>