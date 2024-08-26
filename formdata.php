<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pengajuan Cuti</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Favicons -->
  <link href="assets/img/logodilmil.png" rel="icon">
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
<script>
    // Check for URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    if (status === 'success') {
      Swal.fire({
        title: 'Berhasil!',
        text: 'Pengajuan berhasil diajukan!',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then(() => {
        // Set timeout for 1.5 seconds (1500 milliseconds)
        setTimeout(() => {
          window.location.href = 'formdata.php'; // Redirect after 1.5 seconds
        }, 1500); // 1500 milliseconds = 1.5 seconds
      });
    } else if (status === 'error') {
      Swal.fire({
        title: 'Gagal!',
        text: 'Pengajuan gagal diajukan!',
        icon: 'error',
        confirmButtonText: 'OK'
      });
    } else if (status === 'kuota_habis') {
      Swal.fire({
        title: 'Kuota Cuti Habis!',
        text: 'Kuota cuti Anda telah habis. Tidak dapat mengajukan cuti lagi.',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
    }
</script>
<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}
$foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png'; // Gunakan default jika tidak ada foto
$fotoUrl = 'foto/' . $foto; // Path ke folder tempat foto disimpan
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : ''; // Ambil nama dari session
$nip = isset($_SESSION['user']) ? $_SESSION['nip'] : ''; // Ambil NIP dari session


// Koneksi ke database
include("koneksi.php");

// Query untuk mengambil data pengajuan
$query = "SELECT * FROM tbl_pengajuan WHERE nip = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $_SESSION['user']); // Menggunakan NIP dari session
$stmt->execute();
$result = $stmt->get_result();

// Query untuk menghitung total cuti disetujui
$jumlah_query = "SELECT SUM(totaldiajukan) AS total_cuti_disetujui FROM tbl_pengajuan WHERE nip = ? AND status_pengajuan = 'Disetujui'";
$jumlah_stmt = $koneksi->prepare($jumlah_query);
$jumlah_stmt->bind_param("s", $_SESSION['user']);
$jumlah_stmt->execute();
$jumlah_result = $jumlah_stmt->get_result();
$jumlah_row = $jumlah_result->fetch_assoc();
$total_cuti_disetujui = $jumlah_row['total_cuti_disetujui'] ? $jumlah_row['total_cuti_disetujui'] : 0;
$kuota_habis = $total_cuti_disetujui >= 12;
?>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="userPage.php" class="logo d-flex align-items-center">
        <img src="assets/img/logodilmil.png" alt="">
        <span class="d-none d-lg-block">Pengajuan Cuti</span>
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

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?php echo htmlspecialchars($fotoUrl); ?>" alt="Profile" class="profile-img">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($_SESSION['nama']); ?></h6>
              <span><?php echo htmlspecialchars($_SESSION['role']); ?></span>
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
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Pengajuan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="formdata.php">
              <i class="bi bi-circle"></i><span>Form Pengajuan</span>
            </a>
          </li>
          <li>
            <a href="riwayatpengajuan.php">
              <i class="bi bi-circle"></i><span>Riwayat Pengajuan</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End Forms Nav -->

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Riwayat Cuti</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tabeldata.php">
              <i class="bi bi-circle"></i><span>Riwayat Cuti</span>
            </a>
          </li>
          -->
        </ul>
      </li><!-- End Tables Nav -->

    </ul>
  </aside><!-- End Sidebar-->

  
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Formulir Pengajuan Cuti</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Silahkan isi data berikut :</h5>

              <!-- General Form Elements -->
              <form action="simpan.php" method="POST" <?php echo $kuota_habis ? 'disabled' : ''; ?>>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required disabled>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="text" class="col-sm-2 col-form-label">NIP</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="nip" value="<?php echo htmlspecialchars($nip); ?>" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">No HP</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" name="nohp" required>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Tanggal Mulai Cuti</label>
                  <div class="col-sm-6">
                    <input type="date" class="form-control" name="tglmulai" required>
                  </div>  
                </div>
                
                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Tanggal Berhenti Cuti</label>
                  <div class="col-sm-6">
                    <input type="date" class="form-control" name="tglberhenti" required>
                  </div>  
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Jenis Cuti</label>
                  <div class="col-sm-6">
                    <select class="form-select" aria-label="Default select example" name="jeniscuti" required>
                      <option value="" disabled selected>Pilih</option>
                      <option value="Cuti Tahunan">Cuti Tahunan</option>
                      <option value="Cuti Besar">Cuti Besar</option>
                      <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Keterangan</label>
                  <div class="col-sm-6">
                    <textarea class="form-control" name="keterangan" rows="4" placeholder="Masukkan keterangan" required></textarea>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" <?php echo $kuota_habis ? 'disabled' : ''; ?>>Ajukan</button>
                  </div>
                </div>

              </form>

              <?php if ($kuota_habis): ?>
                <div class="alert alert-warning mt-3" role="alert">
                  Kuota Cuti Anda telah habis. Tidak dapat mengajukan cuti lagi.
                </div>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </section>
  </main><!-- End #main -->

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
