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
  <style>
    .form-select {
      width: 150px;
    }
    .form-select option {
      white-space: nowrap;
    }
  </style>
</head>

<body>
<script>
function updateStatus(selectElement) {
    var form = selectElement.closest('form');
    var id_pengajuan = form.getAttribute('data-id');
    var status = selectElement.value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle successful status update
            if (xhr.responseText.trim() === "Success") {
                Swal.fire('Success', 'Status updated successfully.', 'success');
            } else {
                Swal.fire('Error', 'Error updating status.', 'error');
            }
        } else {
            Swal.fire('Error', 'Error updating status.', 'error');
        }
    };

    xhr.send('id_pengajuan=' + encodeURIComponent(id_pengajuan) + '&status=' + encodeURIComponent(status));
}
</script>

<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png'; // Use default if no photo
$fotoUrl = 'foto/' . $foto; // Path to the folder where photos are stored

// Connect to the database
include("koneksi.php");

// Ambil data dari query string untuk pencarian dan filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

// Paging
$limit = 25;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Query untuk mendapatkan total jumlah data
$total_query = "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE (nama LIKE ? OR nip LIKE ?)";
$params = ["%$search%", "%$search%"];
if (!empty($status_filter)) {
    $total_query .= " AND status_pengajuan = ?";
    $params[] = $status_filter;
}
$stmt_total = $koneksi->prepare($total_query);
$stmt_total->bind_param(str_repeat('s', count($params)), ...$params);
$stmt_total->execute();
$total_result = $stmt_total->get_result()->fetch_assoc();
$total_rows = $total_result['total'];
$total_pages = ceil($total_rows / $limit);

// Query untuk mendapatkan data dengan paging
$query = "SELECT * FROM tbl_pengajuan WHERE (nama LIKE ? OR nip LIKE ?)";
$params = ["%$search%", "%$search%"];
if (!empty($status_filter)) {
    $query .= " AND status_pengajuan = ?";
    $params[] = $status_filter;
}
$query .= " ORDER BY id_pengajuan ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$stmt = $koneksi->prepare($query);
$stmt->bind_param(str_repeat('s', count($params) - 2) . 'ii', ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="adminPage.php" class="logo d-flex align-items-center">
      <img src="assets/img/logodilmil.png" alt="">
      <span class="d-none d-lg-block">Pengajuan Cuti</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
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
            <a class="dropdown-item d-flex align-items-center" href="adminprofil.php">
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
        <i class="bi bi-journal-text"></i><span>Riwayat Cuti</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="riwayatpengajuanAdminPage.php">
            <i class="bi bi-circle"></i><span>Riwayat Pengajuan Cuti</span>
          </a>
        </li>
     
      </ul>
    </li><!-- End Forms Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="tabeldata.php">
            <i class="bi bi-circle"></i><span>Tabel Data Pegawai</span>
          </a>
        </li>
        <li>
                    <a href="tambahdata.php">
                        <i class="bi bi-circle"></i><span>Tambah Data Pegawai</span>
                    </a>
                </li>
      </ul>
    </li><!-- End Tables Nav -->
  </ul>
</aside><!-- End Sidebar -->

<!-- ======= Main ======= -->
<main id="main" class="main">
  <!-- Search and Filter Form -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <form method="GET" action="">
              <div class="row">
                <div class="col-md-3">
                  <div class="mb-1">
                    <label for="search" class="form-label">Cari berdasarkan Nama atau NIP</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Nama atau NIP">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-1">
                    <label for="status_filter" class="form-label">Filter Status Pengajuan</label>
                    <select id="status_filter" name="status_filter" class="form-select">
                      <option value="">Semua Status</option>
                      <option value="Diproses" <?php echo $status_filter == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                      <option value="Disetujui" <?php echo $status_filter == 'Disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                      <option value="Ditolak" <?php echo $status_filter == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="mb-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100"  style="margin-top: 0; padding-top: 8px; padding-bottom: 8px;"><i class="bi bi-search"></i></button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Tabel Data Pengajuan -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <table class="table caption-top">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama</th>
                  <th scope="col">NIP</th>
                  <th scope="col">No HP</th>
                  <!-- <th scope="col">Tanggal Mulai</th>
                  <th scope="col">Tanggal Berhenti</th>
                  <th scope="col">Jenis Cuti</th>
                  <th scope="col">Keterangan</th>
                  <th scope="col">Total Diajukan</th> -->
                  <th scope="col">Tanggal Pengajuan</th>
                  <th scope="col" colspan="2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $i = $offset + 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<th scope='row'>" . $i++ . "</th>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nohp']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['tglmulai']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['tglberhenti']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['jeniscuti']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['keterangan']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['totaldiajukan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>
                          <form class='update-form' data-id='" . htmlspecialchars($row['id_pengajuan']) . "'>
                            <select name='status' class='form-select' aria-label='Default select example' onchange='updateStatus(this)'>
                              <option value='Diproses' " . ($row['status_pengajuan'] == 'Diproses' ? 'selected' : '') . ">Diproses</option>
                              <option value='Disetujui' " . ($row['status_pengajuan'] == 'Disetujui' ? 'selected' : '') . ">Disetujui</option>
                              <option value='Ditolak' " . ($row['status_pengajuan'] == 'Ditolak' ? 'selected' : '') . ">Ditolak</option>
                            </select>
                          </form>
                        </td>";
                        echo "<td><a href='unduh.php?id=" . urlencode($row['id_pengajuan']) . "' class='btn btn-primary'><i class='bi bi-download'></i> </a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Tidak ada data pengajuan.</td></tr>";
                }
                ?>
              </tbody>
            </table>
 <!-- Total Data Tampilkan dan Navigasi Paging -->
 <div class="d-flex justify-content-between align-items-center">
            <div>
              <p>Total: <?php echo $total_rows; ?> data</p>
            </div>
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&status_filter=<?php echo urlencode($status_filter); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                  </li>
                <?php endif; ?>
                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                  <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&status_filter=<?php echo urlencode($status_filter); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                  </li>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                  <li class="page-item">
                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&status_filter=<?php echo urlencode($status_filter); ?>&page=<?php echo $page + 1; ?>">Next</a>
                  </li>
                <?php endif; ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main><!-- End #main -->

<!-- ======= Footer ======= -->
<!-- <footer id="footer" class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="copyright">
          &copy; <strong><span>2024</span></strong>
        </div>
        <div class="credits">

          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>
    </div>
  </div>
</footer> -->

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/jquery/jquery.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
</body>
</html>
