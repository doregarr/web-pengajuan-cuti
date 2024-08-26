<?php
// update_status.php

include("koneksi.php"); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the posted data
    $id_pengajuan = isset($_POST['id_pengajuan']) ? $_POST['id_pengajuan'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($id_pengajuan && $status) {
        // Prepare the SQL statement
        $stmt = $koneksi->prepare("UPDATE tbl_pengajuan SET status_pengajuan = ? WHERE id_pengajuan = ?");
        $stmt->bind_param('si', $status, $id_pengajuan);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error";
        }

        $stmt->close();
    } else {
        echo "Invalid data";
    }
} else {
    echo "Invalid request";
}

$koneksi->close();
?>
