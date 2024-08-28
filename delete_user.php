<?php
session_start();
include("koneksi.php");

// Pastikan user memiliki akses yang tepat
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['nip'])) {
    $nip = $_GET['nip'];

    // Validasi NIP
    if (empty($nip)) {
        $_SESSION['error_message'] = 'NIP tidak valid.';
        header("Location: tabeldata.php");
        exit;
    }

    // Siapkan query SQL
    $query = "DELETE FROM login WHERE nip = ?";

    // Siapkan statement
    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param('s', $nip);

        // Eksekusi query
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Data pengguna telah dihapus.';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus data pengguna: ' . $stmt->error;
        }

        // Tutup statement
        $stmt->close();
    } else {
        $_SESSION['error_message'] = 'Gagal menyiapkan statement: ' . $koneksi->error;
    }

    // Tutup koneksi
    $koneksi->close();
} else {
    $_SESSION['error_message'] = 'NIP tidak ditemukan.';
}

// Redirect ke tabeldata.php
header("Location: tabeldata.php");
exit;
?>
