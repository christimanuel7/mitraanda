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
    $pdf->Cell(20,14,'Tanggal',1,0,'C');
    $pdf->Cell(50,14,'Dari/Untuk' ,1,0,'C');
    $pdf->Cell(70,7,'Pembelian' ,1,0,'C');
    $pdf->Cell(70,7,'Harga Pokok Penjualan' ,1,0,'C');
    $pdf->Cell(70,7,'Persediaan' ,1,1,'C');


    $pdf->Cell(20,7,'',0,0);
    $pdf->Cell(50,7,'',0,0);

    $pdf->Cell(10,7,'Unit',1,0,'C');
    $pdf->Cell(30,7,'Harga',1,0,'C');
    $pdf->Cell(30,7,'Jumlah',1,0,'C');

    $pdf->Cell(10,7,'Unit',1,0,'C');
    $pdf->Cell(30,7,'Unit',1,0,'C');
    $pdf->Cell(30,7,'Jumlah',1,0,'C');

    $pdf->Cell(10,7,'Unit',1,0,'C');
    $pdf->Cell(30,7,'Unit',1,0,'C');
    $pdf->Cell(30,7,'Jumlah',1,1,'C');

    $pdf->SetFont('Times','',10);
    $no=1;
    $data=mysqli_query($conn,"SELECT tbstokmasuk.tanggalMasuk, tbpemasok.Pemasok, tbdetailmasuk.jumlahMasuk, tbdetailmasuk.totalHargaMasuk FROM tbdetailmasuk
    INNER JOIN tbstokmasuk ON tbdetailmasuk.idBarangMasuk=tbstokmasuk.idBarangMasuk
    INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
    WHERE tbdetailmasuk.idProduk='$idProduk'");
    $data2=mysqli_query($conn,"SELECT tbstokkeluar.tanggalKeluar, tbstokkeluar.Keterangan, tbdetailkeluar.jumlahKeluar, tbdetailkeluar.totalHargaKeluar FROM tbdetailkeluar
    INNER JOIN tbstokkeluar ON tbdetailkeluar.idBarangKeluar=tbstokkeluar.idBarangKeluar
    WHERE tbdetailkeluar.idProduk='$idProduk' AND tbstokkeluar.Status='1'
    UNION
    SELECT tbretur.tanggalRetur, tbretur.Alasan, tbretur.jumlahRetur, tbretur.totalHargaRetur FROM tbretur
    WHERE tbretur.idProduk='$idProduk' AND tbretur.Status='1'");
    $totalUnit=0;
    $hargaUnit=0;
    $totalHarga=0;
    while($d=mysqli_fetch_array($data)){
        $tanggalMasuk = $d['tanggalMasuk'];
        $jumlahMasuk =$d['jumlahMasuk'];
        $Pemasok=$d['Pemasok'];
        $totalHargaMasuk =$d['totalHargaMasuk'];
        $hargaMasukPerItem=$totalHargaMasuk/$jumlahMasuk;
        $konversiHargaMasukPerItem= "Rp " . number_format($hargaMasukPerItem,2,',','.');
        $konversiTotalHargaMasuk= "Rp " . number_format($totalHargaMasuk,2,',','.');
        $totalUnit=$totalUnit+$jumlahMasuk;
        $hargaUnit=$hargaMasukPerItem;
        $totalHarga=$totalHarga+$totalHargaMasuk;
        $konversiHargaUnit= "Rp " . number_format($hargaUnit,2,',','.');
        $konversiTotalHarga= "Rp " . number_format($totalHarga,2,',','.');

        $pdf->Cell(20,7,$d['tanggalMasuk'],1,0,'C');
        $pdf->Cell(50,7,$d['Pemasok'],1,0,'L');

        $pdf->Cell(10,7,$d['jumlahMasuk'],1,0,'C');
        $pdf->Cell(30,7,$konversiHargaMasukPerItem,1,0,'C');
        $pdf->Cell(30,7,$konversiTotalHargaMasuk,1,0,'C');

        $pdf->Cell(10,7,'-',1,0,'C');
        $pdf->Cell(30,7,'-',1,0,'C');
        $pdf->Cell(30,7,'-',1,0,'C');

        $pdf->Cell(10,7,$totalUnit,1,0,'C');
        $pdf->Cell(30,7,$konversiHargaUnit,1,0,'C');
        $pdf->Cell(30,7,$konversiTotalHarga,1,1,'C');
    }
    while($d=mysqli_fetch_array($data2)){
        $jumlahKeluar=$d['jumlahKeluar'];
        $totalHargaKeluar =$d['totalHargaKeluar'];
        $hargaKeluarPerItem=$totalHargaKeluar/$jumlahKeluar;
        $konversiHargaKeluarPerItem= "Rp " . number_format($hargaKeluarPerItem,2,',','.');
        $konversiTotalHargaKeluar= "Rp " . number_format($totalHargaKeluar,2,',','.');
        $totalUnit=$totalUnit-$jumlahKeluar;
        $hargaUnit=$hargaKeluarPerItem;
        $totalHarga=$totalHarga-$totalHargaKeluar;
        $konversiHargaUnit= "Rp " . number_format($hargaUnit,2,',','.');
        $konversiTotalHarga= "Rp " . number_format($totalHarga,2,',','.');

        $pdf->Cell(20,7,$d['tanggalKeluar'],1,0,'C');
        $pdf->Cell(50,7,$d['Keterangan'],1,0,'R');

        $pdf->Cell(10,7,'-',1,0,'C');
        $pdf->Cell(30,7,'-',1,0,'C');
        $pdf->Cell(30,7,'-',1,0,'C');

        $pdf->Cell(10,7,$d['jumlahKeluar'],1,0,'C');
        $pdf->Cell(30,7,$konversiHargaKeluarPerItem,1,0,'C');
        $pdf->Cell(30,7,$konversiTotalHargaKeluar,1,0,'C');

        $pdf->Cell(10,7,$totalUnit,1,0,'C');
        $pdf->Cell(30,7,$konversiHargaUnit,1,0,'C');
        $pdf->Cell(30,7,$konversiTotalHarga,1,1,'C');
    }
    $pdf->Output();
?>