<?php
    // memanggil library FPDF
    require ('library/fpdf.php');
    include '../../fungsi.php';
	
	if($_SESSION['Jabatan']=='Checker'){
		header('location:../../index.php');
	}

     // Mengambil Masukan Tanggal Awal dan Akhir
     $awal=$_GET['awal'];
     $akhir=$_GET['akhir'];
 
     $tglAwal=date('d M Y', strtotime($awal));
     $tglAkhir=date('d M Y', strtotime($akhir));
 
     // Jika Masukan Tanggal Awal dan Akhir Ada
     if(!empty($awal) OR !empty($akhir)){
         $Periode="BETWEEN '".$awal."' AND '".$akhir."'";
     }
     // Jika Masukan Tanggal Awal dan Akhir Kosong
     else{
         $Periode="";
     }
            
    // Mengatur Halaman PDF
    $pdf=new FPDF('P','mm','A4');
    $pdf->AddPage('L');
    $pdf->SetTitle('Laporan Stok Barang Retur - TB Mitra Anda');
    $image="../../../../assets/img/logotoko.png";

    $pdf-> Image('profileimage/'.$image,60,16,32,28);
    $pdf->SetFont('Times','B',24);
    $pdf->Cell(288,24,'TB. MITRA ANDA',0,1,'C');
    $pdf->SetFont('Times','',12);
    $pdf->Cell(300,0,'Jl. Limo Raya No.112, Limo, Kec. Limo, Kota Depok, Jawa Barat 16514',0,1,'C');
    $pdf->Cell(280,12,'No. Telp: 081365498675',0,1,'C');
    $pdf->Cell(280,8,'',0,1);
    $pdf->SetFont('Times','',20);
    $pdf->Cell(280,10,'DAFTAR LAPORAN STOK BARANG RETUR',0,1,'C');
    $pdf->Cell(280,4,'',0,1);
    $pdf->SetFont('Times','',16);
    if(!empty($awal) OR !empty($akhir)){
        $pdf->Cell(280,4,'Periode: '.$tglAwal.' - '.$tglAkhir,0,1,'C');
    }else{
        $pdf->Cell(280,4,'',0,1,'C');
    }
    $pdf->Cell(280,8,'',0,1);
    
    // FOR SPACES
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(20,7,'No.',1,0,'C');
    $pdf->Cell(35,7,'Tanggal Masuk' ,1,0,'C');
    $pdf->Cell(35,7,'Tanggal Retur' ,1,0,'C');
    $pdf->Cell(20,7,'ID Produk' ,1,0,'C');
    $pdf->Cell(60,7,'Nama Produk' ,1,0,'C');
    $pdf->Cell(20,7,'Satuan' ,1,0,'C');    
    $pdf->Cell(40,7,'Jumlah' ,1,0,'C');
    $pdf->Cell(50,7,'Alasan' ,1,1,'C');
    
    $pdf->SetFont('Times','',10);
    $no=1;
    $data= mysqli_query($conn,"SELECT *,tbstokmasuk.tanggalMasuk FROM tbretur
    INNER JOIN tbdetailmasuk ON tbretur.idDetailMasuk=tbdetailmasuk.idDetailMasuk
    INNER JOIN tbproduk ON tbretur.idProduk=tbproduk.idProduk
    INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
    INNER JOIN tbstokmasuk ON tbdetailmasuk.idBarangMasuk=tbstokmasuk.idBarangMasuk
    WHERE tbretur.Status='1' AND tanggalRetur $Periode ");
    $hitungTransaksi = mysqli_num_rows($data);
    $totalRetur=0;
    while($d = mysqli_fetch_array($data)){
        $tanggalMasuk= date('d-m-Y', strtotime($d['tanggalMasuk']));
        $tanggalRetur= date('d-m-Y', strtotime($d['tanggalRetur']));
        $pdf->Cell(20,6, $no++,1,0,'C');
        $pdf->Cell(35,6, $tanggalMasuk,1,0,'C');
        $pdf->Cell(35,6, $tanggalRetur,1,0,'C');
        $pdf->Cell(20,6, $d['idProduk'],1,0,'C');
        $pdf->Cell(60,6, $d['Produk'],1,0);
        $pdf->Cell(20,6, $d['Satuan'],1,0,'C');
        $pdf->Cell(40,6, $d['jumlahRetur'],1,0,'C');
        $pdf->Cell(50,6, $d['Alasan'],1,1,'L');
        $totalRetur=$totalRetur+$d['jumlahRetur'];
    }

    $pdf->Cell(230,10, 'Total Transaksi Barang Retur',1,0,'L');
    $pdf->Cell(50,10, $hitungTransaksi,1,1,'C');
    $pdf->Cell(230,10, 'Total Barang Retur',1,0,'L');
    $pdf->Cell(50,10, $totalRetur,1,1,'C');
    
    $pdf->Output();

?>