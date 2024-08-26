<?php
include("koneksi.php");

// Ambil data dari form
$user = $_POST['nip'];
$password = $_POST['password'];

// Query untuk mengambil data pengguna
$sql = "SELECT * FROM login WHERE nip = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data && password_verify($password, $data['passs'])) {
    session_start();
    $_SESSION['user'] = $data['nip'];
    $_SESSION['nama'] = $data['nama']; // Tambahkan session untuk nama
    $_SESSION['role'] = $data['role'];
    $_SESSION['foto'] = $data['foto']; // Tambahkan session untuk foto
    $_SESSION['jabatan'] = $data['jabatan']; // Tambahkan session untuk foto
    $_SESSION['pangkat'] = $data['pangkat']; // Tambahkan session untuk foto

    // Redirect berdasarkan role pengguna
    if ($data['role'] == 'admin') {
        header("Location: adminPage.php");
    } elseif ($data['role'] == 'member') {
        header("Location: userPage.php");
    } else {
        header("Location: login.php?error=invalid");
    }
    exit;
} else {
    header("Location: login.php?error=invalid"); // Redirect ke halaman login dengan parameter error jika login gagal
    exit;
}
?>
