<?php
include("koneksi.php");
session_start(); // Pastikan session dimulai

if (isset($_GET['nip'])) {
    $nip = $_GET['nip'];

    // Validate NIP
    if (empty($nip)) {
        $_SESSION['error_message'] = 'NIP tidak valid.';
        header("Location: tabeldata.php");
        exit;
    }

    // Prepare SQL query
    $query = "DELETE FROM login WHERE nip = ?";

    // Prepare statement
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $nip);

    // Execute query
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Data pengguna telah dihapus.';
    } else {
        $_SESSION['error_message'] = 'Gagal menghapus data pengguna: ' . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$koneksi->close();
header("Location: tabeldata.php");
exit;
?>
