<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Penerimaan Polri</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Favicons -->
  <link href="assets/img/logopol.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
<?php
session_start();
if(empty($_SESSION['user']) OR empty($_SESSION['pass'])){
header("location:login.php");
exit;
}
?>
<?php
include("koneksi.php");
$cari=$_GET['jalur'];
$sql="select * from pendaftaran where jalur='$cari'";
$proses=mysqli_query($koneksi, $sql);
$no=1;
?>
<?php
include("koneksi.php");
$cari=$_GET['jalur'];
if($cari==""){
$sql="select * from pendaftaran ";
$proses=mysqli_query($koneksi, $sql);
$no=1;
}
?>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index1.php" class="logo d-flex align-items-center">
        <img src="assets/img/logopol.png" alt="">
        <span class="d-none d-lg-block">Penerimaan Polri</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    
    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          
        

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/doregarr.jpeg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Priado Siregar</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Priado Siregar</h6>
              <span>Web Designer</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="user.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

         
            <li>
              <hr class="dropdown-divider">
            </li>

           
            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index1.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="formdata.php">
              <i class="bi bi-circle"></i><span>Form Pendaftaran</span>
            </a>
          </li>
          
         
         
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tabeldata.php">
              <i class="bi bi-circle"></i><span>Tabel Data Calon</span>
            </a>
          </li>
         
        </ul>
      </li><!-- End Tables Nav -->

      

  </aside><!-- End Sidebar-->
<form action="cari.php" method="GET">
  

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Tabel Data Calon</h1>
      <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Jalur</label>
                  <div class="col-sm-3">
                    <select class="form-select" aria-label="Default select example" name="jalur">
                      <option selected value="">Pilih</option>
                      <option value="Akpol">Akademi Kepolisian</option>
                      <option value="Bintara Ptu">Bintara PTU</option>
                      <option value="Bakomsus">Bakomsus</option>
                      <option value="Tamtama Brimob">Tamtama Brimob</option>
                      <div class="search-bar">
    
    
   
                    </select>
                             
                    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                  </div>
                </div>

    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
            

              <!-- Default Table -->
              <table class="table">
  <thead>
    <tr class="table-primary">
    <th scope="col">NO</th>
      <th scope="col">NIK</th>
      <th scope="col">Nama</th>
      <th scope="col">E-Mail</th>
      <th scope="col">NO HP</th>
      <th scope="col">Tanggal Lahir</th>
      <th scope="col">Asal Sekolah</th>
      <th scope="col">Jenis Kelamin</th>
      <th scope="col">Jalur Penerimaan</th>
      <th colspan=3 scope="col">Aksi</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  <?php
while ($isi = mysqli_fetch_assoc($proses)) :?>

    <tr>
      <td><?php echo$no++;?></td>
    <td><?php echo $isi['nik']; ?></td>
    <td ><?php echo $isi['nama']; ?></td>
    <td><?php echo $isi['email']; ?></td>
    <td><?php echo $isi['nohp']; ?></td>
    <td><?php echo $isi['tgllahir']; ?></td>
    <td><?php echo $isi['asalsekolah']; ?></td>
    <td><?php echo $isi['jenkel']; ?></td>
    <td><?php echo $isi['jalur']; ?></td>
    <td>  <a type="button" class="btn btn-primary"  href="ubah.php?x=<?php echo $isi['nik']; ?>" ><i class="fa-solid fa-pen-to-square"></i></a></td>
      <td>  <a type="button" class="btn btn-danger" href="hapus.php?x=<?php echo $isi['nik']; ?>"><i class="fa-solid fa-trash"></i></a></td>
      <td>  <a type="button" class="btn btn-secondary" href="cetak.php?x=<?php echo $isi['nik']; ?>"><i class="fa-solid fa-print"></i></a></td>
</tr>
<?php
endwhile;
?>

    
  </tbody>
</table>

  </div>
</div>
   
      

 

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>