<?php
    require '../../fungsi.php';
	
	class buktiReturDownloader {
		private $conn;
		
		public function redirectIndex() {
			if($_SESSION['Jabatan']=='Penjaga Toko'){
				header('location:../../index.php');
			}
		}

		public function __construct($conn)
		{
			$this->conn = $conn;
		}

		public function downloadBuktiRetur($idRetur)
		{
			$sql="SELECT * FROM tbretur
			INNER JOIN tbproduk ON tbretur.idProduk=tbproduk.idProduk
			INNER JOIN tbdetailmasuk ON tbretur.idDetailMasuk=tbdetailmasuk.idDetailMasuk
			WHERE idBarangRetur = '$idRetur'";
			$result = mysqli_query($this->conn, $sql);

			if (mysqli_num_rows($result) == 0) {
				die('File bukti tidak ada.');
			}

			$row = mysqli_fetch_object($result);
			$this->outputBuktiRetur($row->Bukti, $row->Format);
		}

		private function outputBuktiRetur($fileContent, $format)
		{
			header("Content-type: $format");
			echo $fileContent;
		}
	}	
	$buktiReturDownloader = new buktiReturDownloader($conn);
	$idRetur = $_GET['id'];
	$buktiReturDownloader->redirectIndex();
	$buktiReturDownloader->downloadBuktiRetur($idRetur);
?>