<?php
    // memanggil library FPDF
    require ('library/fpdf.php');
    include '../../fungsi.php';
            
    // Mengatur Halaman PDF
    $pdf=new FPDF('P','mm','A4');
    $pdf->AddPage('L');
    $pdf->SetTitle('Laporan Stok Produk - TB Mitra Anda');
    $image="../../../../assets/img/logotoko.png";

    $pdf-> Image('profileimage/'.$image,60,16,32,28);
    $pdf->SetFont('Times','B',24);
    $pdf->Cell(288,24,'TB. MITRA ANDA',0,1,'C');
    $pdf->SetFont('Times','',12);
    $pdf->Cell(300,0,'Jl. Limo Raya No.112, Limo, Kec. Limo, Kota Depok, Jawa Barat 16514',0,1,'C');
    $pdf->Cell(280,12,'No. Telp: 081365498675',0,1,'C');
    $pdf->Cell(280,8,'',0,1);
    $pdf->SetFont('Times','',20);
    $pdf->Cell(280,10,'DAFTAR LAPORAN STOK PRODUK',0,1,'C');
    $pdf->Cell(280,4,'',0,1);
    $pdf->SetFont('Times','',16);
    date_default_timezone_set("Asia/Jakarta");
    $currentdate = 'Tanggal: '.date("d M Y (h:i").' WIB)';
    $pdf->Cell(280,4,$currentdate,0,0,'C');
    $pdf->Cell(280,8,'',0,1);

    $pdf->SetFont('Times','B',9);
    $pdf->Cell(40,7,'ID Produk',1,0,'C');
    $pdf->Cell(80,7,'Nama Produk' ,1,0,'C');
    $pdf->Cell(30,7,'Satuan' ,1,0,'C');
    $pdf->Cell(40,7,'Harga Beli' ,1,0,'C');
    $pdf->Cell(40,7,'Harga Jual' ,1,0,'C');
    $pdf->Cell(50,7,'Stok Produk' ,1,1,'C');
    
    $pdf->SetFont('Times','',10);
    $no=1;
    $data=mysqli_query($conn,"
    SELECT idProduk, Satuan, Produk, hargaBeli, hargaJual, stokProduk FROM tbproduk
    INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
    WHERE tbproduk.Status = '1'
    ORDER BY idProduk");
    $dataHabis=mysqli_query($conn,"SELECT * FROM tbproduk WHERE tbproduk.Status = '1' AND tbproduk.stokProduk=0;");
    $dataKritis=mysqli_query($conn,"SELECT * FROM tbproduk WHERE tbproduk.Status = '1' AND tbproduk.stokProduk>0 AND tbproduk.stokProduk<5;");
    $dataAman=mysqli_query($conn,"SELECT * FROM tbproduk WHERE tbproduk.Status = '1' AND tbproduk.stokProduk>=5;");
    $jumlahItem = mysqli_num_rows($data);
    
    $totalKritis= mysqli_num_rows($dataKritis);
    $totalHabis = mysqli_num_rows($dataHabis);
    $totalStok=0;
    while($d = mysqli_fetch_array($data)){
        $konversiHargaBeli="Rp " . number_format($d['hargaBeli'],2,',','.');
        $konversiHargaJual="Rp " . number_format($d['hargaJual'],2,',','.');
        $pdf->Cell(40,6, $d['idProduk'],1,0,'C');
        $pdf->Cell(80,6, $d['Produk'],1,0,'L');
        $pdf->Cell(30,6, $d['Satuan'],1,0,'C');
        $pdf->Cell(40,6, $konversiHargaBeli,1,0,'C');
        $pdf->Cell(40,6, $konversiHargaJual,1,0,'C');
        $pdf->Cell(50,6, $d['stokProduk'],1,1,'C');
        $totalStok=$totalStok+$d['stokProduk'];
    }

    $pdf->Cell(230,10, 'Total Item Produk',1,0,'L');
    $pdf->Cell(50,10, $jumlahItem,1,1,'C');
    $pdf->Cell(230,10, 'Total Stok Produk',1,0,'L');
    $pdf->Cell(50,10, $totalStok,1,1,'C');
    $pdf->Cell(230,10, 'Total Item Produk (Kurang dari 5)',1,0,'L');
    $pdf->Cell(50,10, $totalKritis,1,1,'C');
    $pdf->Cell(230,10, 'Total Ttem Produk (Habis)',1,0,'L');
    $pdf->Cell(50,10, $totalHabis,1,1,'C');
    
    $pdf->Output();

?>