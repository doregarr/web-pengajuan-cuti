<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Penerimaan Polri</title>
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    .status-disetujui {
        color: green;
        font-weight: bold;
    }
    .status-ditolak {
        color: red;
        font-weight: bold;
    }
    .status-diproses {
        color: orange;
        font-weight: bold;
    }
  </style>

</head>

<body>

<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}
$foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png'; // Gunakan default jika tidak ada foto
$fotoUrl = 'foto/' . $foto; // Path ke folder tempat foto disimpan

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
              <span><?php echo htmlspecialchars($_SESSION['jabatan']); ?></span>
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
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Riwayat Pengajuan Cuti Anda</h1>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <!-- Tabel Data Pengajuan -->
              <table class="table caption-top">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NIP</th>
                    <th scope="col">No HP</th>
                    <th scope="col">Tanggal Mulai</th>
                    <th scope="col">Tanggal Berhenti</th>
                    <th scope="col">Jenis Cuti</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Total Diajukan</th>
                    <th scope="col">Tanggal Pengajuan</th>
                    <th scope="col">Status</th>
                   
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($result->num_rows > 0) {
                      $i = 1;
                      while ($row = $result->fetch_assoc()) {
                          $status = htmlspecialchars($row['status_pengajuan']);
                          $statusClass = '';
                          switch ($status) {
                              case 'Disetujui':
                                  $statusClass = 'status-disetujui';
                                  break;
                              case 'Ditolak':
                                  $statusClass = 'status-ditolak';
                                  break;
                              case 'Diproses':
                                  $statusClass = 'status-diproses';
                                  break;
                              default:
                                  $statusClass = '';
                                  break;
                          }
                          echo "<tr>";
                          echo "<th scope='row'>" . $i++ . "</th>";
                          echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['nohp']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['tglmulai']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['tglberhenti']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['jeniscuti']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['keterangan']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['totaldiajukan']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                          echo "<td><span class='" . $statusClass . "'>" . $status . "</span></td>";
                         

                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='11'>Tidak ada data pengajuan.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>

              <!-- Menampilkan Total Cuti Disetujui -->
              <div class="text-end mt-3">
                <h5>Total Cuti Disetujui: <?php echo number_format($total_cuti_disetujui, 0, ',', '.'); ?> hari</h5>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>
