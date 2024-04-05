<?php
    require 'fungsi.php';
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Tag Head HTML -->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <!--Judul Halaman dan Ikon Aplikasi-->
        <title>TB. Mitra Anda</title>
        <link rel = "icon" href = "../assets/img/logo.png" type = "image/png">

        <!-- Kustomisasi Font Template-->
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!--Kustomisasi CSS Bootstrap-->
        <link href="../css/sb-admin-2.css" rel="stylesheet">
    </head>
    <!-- Akhiran Tag Head HTML -->

    <!-- Tag Body HTML -->
    <body class="bg-gradient-primary">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-4">
                                        <div class="text-center" >
                                            <img class="img-fluid" src="../assets/img/logotoko.png" style="width: 120px;heigth: 120px;"></img>
                                            <h2 class="h2 text-gray-900 mb-4"><b>Login Akun</b></h2>
                                            <p class="p text-gray-900 mb-5">Masukkan username dan password anda untuk mendapatkan akses ke dashboard</p>  
                                        </div>

                                        <!-- Cek Session password salah atau akun tidak aktif -->
                                        <?php 
                                            if($_SESSION['passSalah']=='true'){
                                                echo 
                                                '<div class="alert alert-danger" role="alert">
                                                    <p class = "text-justify">
                                                        Masukan Username dan Password anda salah.<br>Silahkan coba lagi!
                                                    </p>
                                                </div>';
                                            }else if($_SESSION['tidakAktif']=='true'){
                                                echo '<div class="alert alert-warning" role="alert">
                                                    <p class = "text-justify">
                                                    Anda bukan bagian dari TB. Mitra Anda.<br>Silahkan laporkan ke Owner!
                                                    </p>
                                                </div>';
                                            }
                                        ?>

                                        <!-- Formulir login akun Pengguna -->
                                        <form method="post">
                                            <!-- Kolom text pengisian Username -->
                                            <div class="form-group">
                                                <label class="p text-gray-900">Username:</label>
                                                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" name="Username" minlength="8" placeholder="Masukkan Username Anda"  oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini sebanyak 8 karakter atau lebih!')"  onchange="this.setCustomValidity('')" required>
                                            </div>
                                            <!-- Kolom cekbox pengisian Password -->
                                            <div class="form-group">
                                                <label class="p text-gray-900">Password:</label>
                                                <input type="password" class="form-control form-control-user" id="Password" name="Password" minlength="8" placeholder="Masukkan Password Anda" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini sebanyak 8 karakter atau lebih!')" onchange="this.setCustomValidity('')" required>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="show" onclick="lihatPassword()">Lihat Password
                                                </div>
                                            </div>
                                            <!-- Tombol untuk melakukan sesi login akun -->
                                            <Button type="submit" class="btn btn-primary btn-user btn-block" style="margin-top:160px;" name= "login" value="login" oninvalid="this.setCustomValidity('Masukkan teks pada kolom pengisian ini!')">
                                                Login
                                            </Button>
                                        </form>
                                        <!-- Akhiran Formulir login akun Pengguna -->

                                        <!-- Skrip melihat masukan Password yang tersembunyi -->
                                        <script>
                                            function lihatPassword() {
                                                var x = document.getElementById("Password");
                                                if (x.type === "password") {
                                                    x.type = "text";
                                                } else {
                                                    x.type = "password";
                                                }
                                            }
                                        </script>

                                        <!-- Skrip mencegah pengiriman ulang formulir saat halaman di-refresh -->
                                        <script>
                                            if ( window.history.replaceState ) {
                                                window.history.replaceState( null, null, window.location.href );
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript Bootstrap dan Datatables-->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="../js/sb-admin-2.min.js"></script>
    </body>
    <!-- Akhiran Tag Body HTML -->
</html>