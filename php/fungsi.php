<?php
  session_start();
  include 'koneksi.php';
  
  $_SESSION['passSalah']='false';
  $_SESSION['tidakAktif']='false';

  // Proses Setelah User Menekan Tombol Login
  if (isset($_POST['login'])) {
      $Uname = $_POST['Username'];
      $Pass = $_POST['Password'];

      // Hitung Jumlah Data
      $cekLoginPengguna = mysqli_query($conn, "SELECT * FROM tbpengguna WHERE Username='$Uname' AND Password='$Pass'");
      $row = mysqli_num_rows($cekLoginPengguna);
      $data = mysqli_fetch_assoc($cekLoginPengguna);

      // Jika Username dan Password sesuai
      if ($row > 0) {
          // Jika Status Pengguna aktif (1)
          if ($data['Status'] > 0) {
              $_SESSION['login'] = 'true';
              $_SESSION['idPengguna'] = $data['idPengguna'];
              $_SESSION['Username'] = $data['Username'];
              $_SESSION['Password'] = $data['Password'];
              $_SESSION['noTelepon'] = $data['noTelepon'];
              $_SESSION['Jabatan'] = $data['Jabatan'];
              $_SESSION['Nama'] = $data['Nama'];
              header('location:index.php');
          }
          // Jika Status Pengguna tidak aktif (1)
          else {
              $_SESSION['tidakAktif'] = 'true';
          }
      }
      // Jika Username dan Password tidak sesuai
      else {
          $_SESSION['passSalah'] = 'true';
      }
  }
?>