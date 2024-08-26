<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include("koneksi.php");

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $pangkat = $_POST['pangkat'];
    $jabatan = $_POST['jabatan'];
    $password = $_POST['password']; // Password dari formulir
    $role = $_POST['role'];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Tangani unggahan foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $foto = basename($_FILES['foto']['name']);
        $uploadDir = 'foto/';
        $uploadFile = $uploadDir . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            $response['message'] = 'Gagal mengunggah foto.';
            echo json_encode($response);
            exit;
        }
    }

    // Query untuk menambahkan data
    $query = "INSERT INTO login (nip, nama, pangkat, jabatan, passs, role" . ($foto ? ", foto" : "") . ") VALUES (?, ?, ?, ?, ?, ?" . ($foto ? ", ?" : "") . ")";
    $stmt = $koneksi->prepare($query);
    
    if ($foto) {
        $stmt->bind_param('sssssss', $nip, $nama, $pangkat, $jabatan, $hashedPassword, $role, $foto);
    } else {
        $stmt->bind_param('sssss', $nip, $nama, $pangkat, $jabatan, $hashedPassword, $role);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Data pegawai telah ditambahkan.';
    } else {
        $response['message'] = 'Gagal menambahkan data pegawai.';
    }

    $stmt->close();
    $koneksi->close();

    echo json_encode($response);
}
?>
