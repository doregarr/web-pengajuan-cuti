<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tentukan folder penyimpanan foto
    $targetDir = "foto/";
    $nip = $_POST['nip'];
    
    // Ambil informasi file yang di-upload
    $fileName = basename($_FILES["profilePhoto"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
    // Validasi tipe file yang diizinkan
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowedTypes)) {
        // Upload file ke server
        if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $targetFilePath)) {
            // Jika berhasil di-upload, simpan nama file ke database atau session
            // Misal, menyimpan nama file ke session untuk digunakan pada halaman lain
            $_SESSION['foto'] = $fileName;

            // Anda bisa menyimpan nama file ke database sesuai dengan NIP user
            // Koneksi ke database dan update data
            // Contoh query:
            // $query = "UPDATE users SET foto = '$fileName' WHERE nip = '$nip'";
            // mysqli_query($conn, $query);

            // Redirect kembali ke halaman profil dengan status sukses
            header("Location: user.php?status=success");
            exit;
        } else {
            // Jika gagal upload
            header("Location: user.php?status=error");
            exit;
        }
    } else {
        // Jika tipe file tidak diizinkan
        header("Location: user.php?status=invalid_type");
        exit;
    }
} else {
    header("Location: user.php");
    exit;
}
