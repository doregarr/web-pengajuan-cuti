<?php
include("koneksi.php");
session_start(); // Pastikan session dimulai

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $pangkat = $_POST['pangkat'];
    $jabatan = $_POST['jabatan'];
    $role = $_POST['role'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

    // Validate NIP
    if (empty($nip) || empty($nama) || empty($pangkat) || empty($jabatan) || empty($role)) {
        $_SESSION['error_message'] = 'Semua field wajib diisi.';
        header("Location: tabeldata.php");
        exit;
    }

    // Prepare SQL query
    $query = "UPDATE login SET nama = ?, pangkat = ?, jabatan = ?, role = ?";

    // If password is set, include it in the query
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query .= ", passs = ?";
    }

    // Handle photo upload
    if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'foto/';
        $targetFile = $targetDir . basename($foto['name']);
        if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
            $query .= ", foto = ?";
            $fotoPath = $foto['name'];
        } else {
            $_SESSION['error_message'] = 'Gagal mengunggah foto.';
            header("Location: tabeldata.php");
            exit;
        }
    }

    $query .= " WHERE nip = ?";

    // Prepare statement
    $stmt = $koneksi->prepare($query);

    // Bind parameters
    $paramTypes = 'ssss';
    $params = [$nama, $pangkat, $jabatan, $role];
    if (!empty($password)) {
        $params[] = $hashedPassword;
        $paramTypes .= 's';
    }
    if (isset($fotoPath)) {
        $params[] = $fotoPath;
        $paramTypes .= 's';
    }
    $params[] = $nip;
    $paramTypes .= 's';

    $stmt->bind_param($paramTypes, ...$params);

    // Execute query
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Data pengguna telah diperbarui.';
        $_SESSION['foto'] = $fotoPath ?? ''; // Update session with the new photo path
    } else {
        $_SESSION['error_message'] = 'Gagal memperbarui data pengguna: ' . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$koneksi->close();
header("Location: tabeldata.php");
exit;
?>
