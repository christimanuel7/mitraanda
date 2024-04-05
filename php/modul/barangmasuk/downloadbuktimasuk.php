<?php
    require '../../fungsi.php';

	if ($_SESSION['Jabatan'] == 'Penjaga Toko') {
        header('location:../../index.php');
    }
	
	$idMasuk = $_GET['id'];
	
	$sql = "SELECT * FROM tbstokmasuk 
	INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
	INNER JOIN tbpengguna ON tbstokmasuk.idPengguna=tbpengguna.idPengguna
	WHERE idBarangMasuk = '$idMasuk'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 0) {
		die('File bukti tidak ada.');
	}

	$row=mysqli_fetch_object($result);
    header("Content-type: ".$row->Format);
    echo $row->Bukti;
?>