<?php
require('fpdf/fpdf.php'); // Pastikan path ini benar

// Koneksi ke database
include("koneksi.php");

// Mulai sesi
session_start();


// Ambil ID pengajuan dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID pengajuan tidak valid.');
}

$id_pengajuan = intval($_GET['id']); // Menggunakan intval untuk keamanan

// Debugging: Tulis ID pengajuan ke log
error_log('ID Pengajuan dari URL: ' . $id_pengajuan);

// Ambil data pengajuan dari database berdasarkan ID
$query = "SELECT * FROM tbl_pengajuan WHERE id_pengajuan = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_pengajuan);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Debugging: Tulis data ke log
if ($data) {
    error_log('Data Ditemukan: ' . print_r($data, true));
} else {
    error_log('Data Tidak Ditemukan untuk ID: ' . $id_pengajuan);
    die('Data pengajuan tidak ditemukan.');
}

// Ambil NIP dan Nama untuk nama file
$nip = htmlspecialchars($data['nip']);
$nama = htmlspecialchars($data['nama']);
$nama_file = $nip . "_" . $nama . ".pdf";

// Buat instance FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(0, 10, 'Persetujuan Pengajuan Cuti', 0, 1, 'C');

// Data Pengajuan
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'ID Pengajuan: ' . htmlspecialchars($data['id_pengajuan']), 0, 1);
$pdf->Cell(0, 10, 'Nama: ' . htmlspecialchars($data['nama']), 0, 1);
$pdf->Cell(0, 10, 'NIP: ' . htmlspecialchars($data['nip']), 0, 1);
$pdf->Cell(0, 10, 'Tanggal Mulai: ' . htmlspecialchars($data['tglmulai']), 0, 1);
$pdf->Cell(0, 10, 'Tanggal Berhenti: ' . htmlspecialchars($data['tglberhenti']), 0, 1);
$pdf->Cell(0, 10, 'Jenis Cuti: ' . htmlspecialchars($data['jeniscuti']), 0, 1);
$pdf->Cell(0, 10, 'Keterangan: ' . htmlspecialchars($data['keterangan']), 0, 1);
$pdf->Cell(0, 10, 'Status Pengajuan: ' . htmlspecialchars($data['status_pengajuan']), 0, 1);
$pdf->Cell(0, 10, 'Total Diajukan: ' . htmlspecialchars($data['totaldiajukan']) . ' hari', 0, 1);

// Tanda Tangan
$pdf->Ln(20);
$pdf->Cell(0, 10, 'Medan, ' . date('j F Y'), 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 10, str_repeat('_', 30), 0, 1, 'R');

// Ambil nama kepala dari tabel login
$query_user = "SELECT nama, pangkat, jabatan FROM login WHERE jabatan = 'Kepala Pengadilan'";
$result_user = $koneksi->query($query_user);
$row_user = $result_user->fetch_assoc();
$nama_kepala = htmlspecialchars($row_user['nama']);
$pangkat = htmlspecialchars($row_user['pangkat']);
$jabatan_kepala = htmlspecialchars($row_user['jabatan']);

$pdf->Cell(0, 10, $pangkat . '. ' . $nama_kepala, 0, 1, 'R');
$pdf->Cell(0, 10, $jabatan_kepala, 0, 1, 'R');

// Output PDF dengan nama file yang disesuaikan
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $nama_file . '"');
$pdf->Output('D'); // 'D' untuk memaksa download

// Akhiri script setelah output PDF
exit;
?>
