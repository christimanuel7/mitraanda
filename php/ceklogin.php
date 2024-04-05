<?php
	// Jika Login Tidak Berhasil, Pengguna Diarahkan Ke Halaman Login
	if(isset($_SESSION['login'])){
	}else{
		header('location:login.php');
	}
?>