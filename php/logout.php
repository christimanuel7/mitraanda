<?php
	// Menghapus Semua Sesi di Aplikasi dan Mengarahkan Ke Halaman Login
	session_start();
	session_destroy();
	header('location: login.php');
	exit();
?>