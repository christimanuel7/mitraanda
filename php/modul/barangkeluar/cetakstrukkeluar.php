<?php
    // Memanggil Library FPDF
    require ('../laporan/library/fpdf.php');
    include '../../fungsi.php';

    if($_SESSION['Jabatan']=='Checker'){
		header('location:../../index.php');
	}

    $idBarangKeluar = $_GET['id'];
    $data = mysqli_query($conn,"SELECT * FROM `tbdetailkeluar` 
    INNER JOIN tbstokkeluar ON tbdetailkeluar.idBarangKeluar=tbstokkeluar.idBarangKeluar 
    INNER JOIN tbproduk ON tbdetailkeluar.idProduk=tbproduk.idProduk
    INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
    WHERE tbstokkeluar.idBarangKeluar='$idBarangKeluar' AND tbstokkeluar.Status=1
    ORDER BY tbdetailkeluar.idDetailKeluar");
    $rowBarangKeluar =mysqli_fetch_array($data);

	$tanggalKeluar = $rowBarangKeluar['tanggalKeluar'];
    $Keterangan = $rowBarangKeluar['Keterangan'];
         
    // Mengatur Halaman PDF
    $pdf = new FPDF('P','cm',[18,12]);
    $pdf->AddPage('P');
    $pdf->SetTitle('Laporan Stok Barang Masuk - TB Mitra Anda');
    $image="../../../../assets/img/logo.png";

    $pdf-> Image('profileimage/'.$image,1,1,3,2);
    $pdf->SetFont('Times','B',20);
    $pdf->Cell(13,1,'MITRA ANDA',0,1,'C');
    $pdf->SetFont('Times','',12);
    $pdf->Cell(13,0,'material & equipment',0,1,'C');
    $pdf->SetFont('Times','',9);
    $pdf->Cell(13,1,'Bahan Bangunan, Alat Listrik, Teknik, Kayu, Besi',0,1,'C');
    $pdf->Cell(13,0,'Baja, Sanitary, Spandek, Bondek, Wiremesh',0,1,'C');
    $pdf->Cell(0,0,'',0,1);
    $pdf->SetFont('Times','',10);
    $pdf->Cell(10,1,'Jl. Limo Raya No.112, Limo, Kec. Limo, Kota Depok, Jawa Barat 16514',0,1,'C');
    $pdf->Line(0, 4, 600, 4);
    $pdf->Cell(0, 0.5,'',0,1); 
    $pdf->SetFont('times','B',9);
    $pdf->Cell(10,0,'Tanggal: '.$tanggalKeluar,0,1,'L');
    $pdf->Cell(10,1,'Kepada Yth.:'.$Keterangan,0,1,'L');

    // Menghitung lebar halaman pdf
    $pageWidth = $pdf->GetPageWidth();
    // Menyesuaikan lebar sel dengan lebar halaman PDF
    // $cellWidth1 = ($pageWidth - 2) * 0.1; // 10% dari lebar halaman
    $cellWidth1 = ($pageWidth - 2) * 0.2; // 20% dari lebar halaman
    $cellWidth2 = ($pageWidth - 2) * 0.4; // 40% dari lebar halaman
    $cellWidth3 = ($pageWidth - 2) * 0.2; // 10% dari lebar halaman
    $cellWidth4 = ($pageWidth - 2) * 0.2; // 20% dari lebar halaman
    // Membuat sel-sel dengan lebar yang sudah disesuaikan
    $pdf->Cell($cellWidth1, 1, 'Banyaknya', 1, 0, 'C');
    $pdf->Cell($cellWidth2, 1, 'Nama Barang', 1, 0, 'C');
    $pdf->Cell($cellWidth3, 1, 'Harga', 1, 0, 'C');
    $pdf->Cell($cellWidth4, 1, 'Jumlah', 1, 1, 'C'); // Mengakhiri baris

    $pdf->SetFont('times','',8);
    $idBarangKeluar = $_GET['id'];
    $data = mysqli_query($conn,"SELECT * FROM `tbdetailkeluar` 
    INNER JOIN tbstokkeluar ON tbdetailkeluar.idBarangKeluar=tbstokkeluar.idBarangKeluar 
    INNER JOIN tbproduk ON tbdetailkeluar.idProduk=tbproduk.idProduk
    INNER JOIN tbsatuan ON tbproduk.idSatuan=tbsatuan.idSatuan
    WHERE tbstokkeluar.idBarangKeluar='$idBarangKeluar' AND tbstokkeluar.Status=1
    ORDER BY tbdetailkeluar.idDetailKeluar");
    $totalHarga=0;
    while($d = mysqli_fetch_array($data)){
        $hargaJumlah=$d['hargaKeluar']*$d['jumlahKeluar'];
        $konversiHargaKeluar = "Rp " . number_format($d['hargaKeluar'],2,',','.');
        $konversiHargaJumlah = "Rp " . number_format($hargaJumlah,2,',','.');
        // $tanggalMasuk= date('d-m-Y', strtotime($d['tanggalMasuk']));
        $pdf->Cell(2,1, $d['jumlahKeluar'],1,0,'C');
        $pdf->Cell(4,1, $d['Produk'],1,0,'C');
        $pdf->Cell(2,1, $konversiHargaKeluar,1,0,'C');
        $pdf->Cell(2,1, $konversiHargaJumlah,1,1,'C');
        $totalHarga=$totalHarga+$hargaJumlah;
    }
    $konversiTotalHarga = "Rp " . number_format($totalHarga,2,',','.');
    $cellWidth5 = ($pageWidth - 2) * 0.8; // 20% dari lebar halaman
    $cellWidth6 = ($pageWidth - 2) * 0.2; // 20% dari lebar halaman
    $pdf->Cell($cellWidth5, 1, 'Total', 1, 0, 'C'); // Mengakhiri baris
    $pdf->Cell($cellWidth6, 1, $konversiTotalHarga, 1, 1, 'C'); // Mengakhiri baris
    $pdf->Output();
?>