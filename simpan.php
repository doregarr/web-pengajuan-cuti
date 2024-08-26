<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta'); // Set timezone ke WIB

$foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png'; // Gunakan default jika tidak ada foto
$fotoUrl = 'foto/' . $foto; // Path ke folder tempat foto disimpan
$namaa = isset($_SESSION['nama']) ? $_SESSION['nama'] : ''; // Ambil nama dari session
// $nip = isset($_SESSION['user']) ? $_SESSION['nip'] : ''; // Ambil NIP dari session
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("koneksi.php");

// Generate kode unik untuk ID
require 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;

$id = Uuid::uuid4()->toString();

// Mengambil data dari form
// $nama = $_POST['nama'];
$nip = $_POST['nip'];
$nohp = $_POST['nohp'];
$tglmulai = $_POST['tglmulai'];
$tglberhenti = $_POST['tglberhenti'];
$jeniscuti = $_POST['jeniscuti'];
$keterangan = $_POST['keterangan'];
$status = 'Diproses';

// Menghitung total hari cuti
$datetime1 = new DateTime($tglmulai);
$datetime2 = new DateTime($tglberhenti);
$interval = $datetime1->diff($datetime2);
$totalcuti = $interval->days + 1;

// Mendapatkan timestamp saat ini sesuai WIB
$created_at = date('Y-m-d H:i:s');

// Menyimpan data ke database menggunakan prepared statement
$sql = "INSERT INTO tbl_pengajuan (id_pengajuan, nama, nip, nohp, tglmulai, tglberhenti, jeniscuti, keterangan, totaldiajukan, created_at, status_pengajuan)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("ssssssssiss", $id, $namaa, $nip, $nohp, $tglmulai, $tglberhenti, $jeniscuti, $keterangan, $totalcuti, $created_at, $status);

if ($stmt->execute()) {
    // Mengarahkan ke halaman yang sama dengan parameter query untuk notifikasi
    header("Location: formdata.php?status=success");
    exit;
} else {
    // Menampilkan pesan kesalahan
    echo "Error: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>
