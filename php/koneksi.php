<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbmitraanda";
    
    // Membuat Koneksi Database
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    // Mengecek Koneksi Database
    if (!$conn) {
      die("Koneksi gagal: " . mysqli_connect_error());
    }
?>