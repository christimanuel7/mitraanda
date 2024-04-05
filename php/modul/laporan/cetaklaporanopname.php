<?php
    // Memanggil Library FPDF
    require ('library/fpdf.php');
    include '../../fungsi.php';
	
	if($_SESSION['Jabatan']=='Penjaga Toko'){
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
    $pdf->SetTitle('Laporan Opname Barang - TB Mitra Anda');
    $image="../../../../assets/img/logotoko.png";

    $pdf-> Image('profileimage/'.$image,60,16,32,28);
    $pdf->SetFont('Times','B',24);
    $pdf->Cell(288,24,'TB. MITRA ANDA',0,1,'C');
    $pdf->SetFont('Times','',12);
    $pdf->Cell(300,0,'Jl. Limo Raya No.112, Limo, Kec. Limo, Kota Depok, Jawa Barat 16514',0,1,'C');
    $pdf->Cell(280,12,'No. Telp: 081365498675',0,1,'C');
    $pdf->Cell(280,8,'',0,1);
    $pdf->SetFont('Times','',20);
    $pdf->Cell(280,10,'DAFTAR LAPORAN OPNAME BARANG',0,1,'C');
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
    $pdf->Cell(10,7,'No.',1,0,'C');
    $pdf->Cell(20,7,'Tanggal' ,1,0,'C');
    $pdf->Cell(40,7,'Keterangan' ,1,0,'C');
    $pdf->Cell(15,7,'ID Produk' ,1,0,'C');
    $pdf->Cell(65,7,'Nama Produk' ,1,0,'C');
    $pdf->Cell(20,7,'Satuan' ,1,0,'C');
    $pdf->Cell(25,7,'Jumlah Sistem' ,1,0,'C');
    $pdf->Cell(25,7,'Jumlah Opname' ,1,0,'C');
    $pdf->Cell(40,7,'Alasan' ,1,0,'C');
    $pdf->Cell(20,7,'Pemeriksa' ,1,1,'C');
    
    $pdf->SetFont('Times','',10);
    $no=1;
    $data = mysqli_query($conn,"SELECT * FROM `tbdetailopname` 
    INNER JOIN tbopname ON tbdetailopname.idOpname=tbopname.idOpname
    INNER JOIN tbproduk ON tbdetailopname.idProduk=tbproduk.idProduk
    INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
    INNER JOIN tbpengguna ON tbopname.idPengguna=tbpengguna.idPengguna
    WHERE tbopname.Status=1 AND tanggalOpname $Periode
    ORDER BY tbdetailopname.idDetailOpname");
    $hitungOpname = mysqli_num_rows($data);
    while($d = mysqli_fetch_array($data)){
        $tanggalOpname= date('d-m-Y', strtotime($d['tanggalOpname']));
        $pdf->Cell(10,6, $no++,1,0,'C');
        $pdf->Cell(20,6, $tanggalOpname,1,0,'C');
        $pdf->Cell(40,6, $d['Keterangan'],1,0,'C');
        $pdf->Cell(15,6, $d['idProduk'],1,0,'C');
        $pdf->Cell(65,6, $d['Produk'],1,0);
        $pdf->Cell(20,6, $d['Satuan'],1,0,'C');
        $pdf->Cell(25,6, $d['jumlahSistem'],1,0,'C');
        $pdf->Cell(25,6, $d['jumlahFisik'],1,0,'C');
        $pdf->Cell(40,6, $d['Alasan'],1,0,'L');
        $pdf->Cell(20,6, $d['Nama'],1,1,'C');
    }

    $pdf->Cell(260,10, 'Total Kegiatan Opname Barang',1,0,'L');
    $pdf->Cell(20,10, $hitungOpname,1,1,'C');
    
    $pdf->Output();

?>