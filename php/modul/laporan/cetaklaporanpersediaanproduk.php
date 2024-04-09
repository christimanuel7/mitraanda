<?php
    // memanggil library FPDF
    require ('library/fpdf.php');
    include '../../fungsi.php';

    $fetchIdProduk=$_GET['id'];
    $query=mysqli_query($conn, "SELECT * FROM tbproduk WHERE idProduk='$fetchIdProduk'");

    $row=mysqli_fetch_array($query);

    $idProduk=$row['idProduk'];
    $Produk=$row['Produk'];
            
    // Mengatur Halaman PDF
    $pdf=new FPDF('P','mm','A4');
    $pdf->AddPage('L');
    $pdf->SetTitle('Laporan Persediaan Produk - TB Mitra Anda');
    $image="../../../../assets/img/logotoko.png";

    $pdf->Image('profileimage/'.$image,60,16,32,28);
    $pdf->SetFont('Times','B',24);
    $pdf->Cell(288,24,'TB. MITRA ANDA',0,1,'C');
    $pdf->SetFont('Times','',12);
    $pdf->Cell(300,0,'Jl. Limo Raya No.112, Limo, Kec. Limo, Kota Depok, Jawa Barat 16514',0,1,'C');
    $pdf->Cell(280,12,'No. Telp: 081365498675',0,1,'C');
    $pdf->Cell(280,8,'',0,1);
    $pdf->SetFont('Times','',20);
    $pdf->Cell(280,10,'DAFTAR LAPORAN PERSEDIAAN PRODUK',0,1,'C');
    $pdf->Cell(280,4,'',0,1);
    $pdf->SetFont('Times','',16);
    date_default_timezone_set("Asia/Jakarta");
    $currentdate = 'Tanggal: '.date("d M Y (h:i").' WIB)';
    $pdf->Cell(280,4,$currentdate,0,0,'C');
    $pdf->Cell(280,16,'',0,1);

    $pdf->SetFont('Times','',12);
    $pdf->Cell(280,4,'Nama Produk: '.$Produk,0,1);
    $pdf->Cell(280,2,'',0,1);

    $pdf->SetFont('Times','B',9);
    $pdf->Cell(30,14,'Tanggal',1,0,'C');
    $pdf->Cell(120,14,'Keterangan' ,1,0,'C');
    $pdf->Cell(80,7,'Jumlah Barang' ,1,0,'C');
    $pdf->Cell(50,7,'Sisa Stok' ,1,1,'C');

    $pdf->Cell(100,7,'',0,0);
    $pdf->Cell(50,7,'',0,0);

    $pdf->Cell(40,7,'Masuk',1,0,'C');
    $pdf->Cell(40,7,'Keluar',1,1,'C');

    $pdf->SetFont('Times','',10);
    $no=1;

    // Data Tabel
    $tampilDetailProduk=mysqli_query($conn,"SELECT * FROM tblog WHERE idProduk='$idProduk'");
    while($d=mysqli_fetch_array($tampilDetailProduk)){
      
        $pdf->Cell(30,7,$d['Tanggal'],1,0,'C');
        $pdf->Cell(120,7,$d['Keterangan'],1,0,'L');
        
        if($d['stokMasuk']!=0){
            $pdf->Cell(40,7,$d['stokMasuk'],1,0,'C');
        }else{
            $pdf->Cell(40,7,'-',1,0,'C');
        }

        $stokkeluarreturopname='-';
        if($d['stokKeluar']!=0){
            $stokkeluarreturopname=$d['stokKeluar'];
    }
    if($d['stokRetur']!=0){
        $stokkeluarreturopname=$d['stokRetur'];
    }
    if($d['stokOpname']!=0){
        $stokkeluarreturopname=$d['stokOpname'];
    }
    $pdf->Cell(40, 7, $stokkeluarreturopname, 1, 0, 'C');

    $pdf->Cell(50, 7, $d['totalStok'], 1, 1, 'C');
}
    $pdf->Output();
?>