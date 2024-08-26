<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png'; // Use default if no photo
$fotoUrl = 'foto/' . $foto; // Path to the folder where photos are stored
// Include database connection
include("koneksi.php");

// Initialize variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 25;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Query to get the total number of records
$total_query = "SELECT COUNT(*) AS total FROM login WHERE (nama LIKE ? OR nip LIKE ?)";
$params = ["%$search%", "%$search%"];
$stmt_total = $koneksi->prepare($total_query);
$stmt_total->bind_param(str_repeat('s', count($params)), ...$params);
$stmt_total->execute();
$total_result = $stmt_total->get_result()->fetch_assoc();
$total_rows = $total_result['total'];
$total_pages = ceil($total_rows / $limit);

// Query to get the data with pagination
$query = "SELECT nip, nama, pangkat, jabatan, foto, role FROM login WHERE (nama LIKE ? OR nip LIKE ?)";
$params = ["%$search%", "%$search%"];
$query .= " ORDER BY nip ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$stmt = $koneksi->prepare($query);
$stmt->bind_param(str_repeat('s', count($params) - 2) . 'ii', ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Data Pengguna</title>
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
        .profile-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>

<body>
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="adminPage.php" class="logo d-flex align-items-center">
            <img src="assets/img/logodilmil.png" alt="">
            <span class="d-none d-lg-block">Data Pengguna</span>
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
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="search" class="form-label">Cari berdasarkan Nama atau NIP</label>
                                        <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Nama atau NIP">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100" style="margin-top: 0; padding-top: 8px; padding-bottom: 8px;"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Tabel Data Pengguna -->
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tabel Data Pengguna</h5>

                    <!-- Table -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Pangkat</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Foto</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                $i = $offset + 1;
                                while ($row = $result->fetch_assoc()) {
                                    $fotoUrl = 'foto/' . htmlspecialchars($row['foto']); // Path to the folder where photos are stored
                                    echo "<tr>";
                                    echo "<th scope='row'>" . $i++ . "</th>";
                                    echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['pangkat']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
                                    echo "<td><img src='$fotoUrl' class='profile-img' alt='Foto'></td>";
                                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                    echo "<td>
                                        <button class='btn btn-warning btn-edit' data-bs-toggle='modal' data-bs-target='#editModal' data-nip='" . htmlspecialchars($row['nip']) . "' data-nama='" . htmlspecialchars($row['nama']) . "' data-pangkat='" . htmlspecialchars($row['pangkat']) . "' data-jabatan='" . htmlspecialchars($row['jabatan']) . "' data-role='" . htmlspecialchars($row['role']) . "' data-foto='" . htmlspecialchars($row['foto']) . "'>Edit</button>
                                        <button class='btn btn-danger btn-delete' data-nip='" . htmlspecialchars($row['nip']) . "'>Hapus</button>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>Tidak ada data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Showing info -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span>Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $result->num_rows, $total_rows); ?> of <?php echo $total_rows; ?> entries</span>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                    <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $p; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $p; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
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
</main><!-- End Main -->

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="update_user.php" enctype="multipart/form-data">
                    <input type="hidden" name="nip" id="editNip">
                    
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editNama" name="nama" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPangkat" class="form-label">Pangkat</label>
                        <input type="text" class="form-control" id="editPangkat" name="pangkat" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editJabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="editJabatan" name="jabatan" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="editPassword" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role</label>
                        <select id="editRole" name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="member">User</option>
                            <!-- Tambahkan opsi lain sesuai kebutuhan -->
                        </select>
                    </div>
                    
                    <!-- <div class="mb-3">
                        <label for="editFoto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="editFoto" name="foto">
                        <img id="editFotoPreview" src="" alt="Preview" class="profile-img mt-2" style="display: none;">
                    </div> -->
                    
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- End Edit Modal -->


<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/jquery/jquery.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle Edit Button Click
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            const nip = this.getAttribute('data-nip');
            const nama = this.getAttribute('data-nama');
            const pangkat = this.getAttribute('data-pangkat');
            const jabatan = this.getAttribute('data-jabatan');
            const role = this.getAttribute('data-role');
            const foto = this.getAttribute('data-foto');

            // Set values in the form
            document.getElementById('editNip').value = nip;
            document.getElementById('editNama').value = nama;
            document.getElementById('editPangkat').value = pangkat;
            document.getElementById('editJabatan').value = jabatan;

            // Set role in select box
            const roleSelect = document.getElementById('editRole');
            roleSelect.value = role;

            // Show current photo in the modal
            const editFotoPreview = document.getElementById('editFotoPreview');
            if (foto) {
                editFotoPreview.src = 'foto/' + foto;
                editFotoPreview.style.display = 'block';
            } else {
                editFotoPreview.style.display = 'none';
            }

            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });
    });

    // Handle Foto Preview
    document.getElementById('editFoto').addEventListener('change', function () {
        const file = this.files[0];
        const preview = document.getElementById('editFotoPreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Handle Delete Button Click
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const nip = this.getAttribute('data-nip');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat membatalkan aksi ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Use window.location to delete and refresh
                    window.location.href = 'delete_user.php?nip=' + nip;
                }
            });
        });
    });
});
</script>
</body>
</html>
