<?php
// Pastikan tidak ada output sebelum kode PHP ini
header('Content-Type: application/json');

include("koneksi.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "SELECT nohp, tglmulai, tglberhenti, jeniscuti, keterangan, totaldiajukan, status_pengajuan FROM tbl_pengajuan WHERE id_pengajuan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID tidak valid']);
}

$koneksi->close();
?>
