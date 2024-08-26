<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Memastikan variabel $koneksi tersedia
if (!isset($koneksi)) {
    die('Koneksi database tidak tersedia.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $_SESSION['user']; // Ambil NIP dari session
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi password baru dan konfirmasi
    if ($newPassword !== $confirmPassword) {
        header("Location: adminprofil.php?status=confirm_mismatch");
        exit;
    }

    // Validasi panjang password baru
    if (strlen($newPassword) < 6) {
        header("Location: adminprofil.php?status=short_password");
        exit;
    }

    // Ambil password yang sudah di-hash dari database
    $stmt = $koneksi->prepare('SELECT passs FROM login WHERE nip = ?');
    if (!$stmt) {
        die('Prepare failed: ' . $koneksi->error);
    }
    $stmt->bind_param('s', $nip);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    // Verifikasi password lama
    if (password_verify($currentPassword, $hashedPassword)) {
        // Hash password baru
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password di database
        $updateStmt = $koneksi->prepare('UPDATE login SET passs = ? WHERE nip = ?');
        if (!$updateStmt) {
            die('Prepare failed: ' . $koneksi->error);
        }
        $updateStmt->bind_param('ss', $newHashedPassword, $nip);
        if ($updateStmt->execute()) {
            header("Location: adminprofil.php?status=success");
            exit;
        } else {
            header("Location: adminprofil.php?status=error");
            exit;
        }
        $updateStmt->close();
    } else {
        header("Location: adminprofil.php?status=wrong_password");
        exit;
    }
}
?>
