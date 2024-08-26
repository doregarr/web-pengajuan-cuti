<?php
include("koneksi.php");

// Ambil data dari form
$user = $_POST['nip'];
$nama = $_POST['nama'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $_POST['role'];

// Proses upload foto
$targetDir = "foto/"; // Folder untuk menyimpan foto
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Buat folder jika tidak ada
}

$targetFile = $targetDir . basename($_FILES["photo"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Cek jika file adalah gambar
$check = getimagesize($_FILES["photo"]["tmp_name"]);
if ($check === false) {
    echo "File bukan gambar.";
    $uploadOk = 0;
}

// Cek ukuran file (misalnya maksimal 5MB)
if ($_FILES["photo"]["size"] > 5000000) {
    echo "File terlalu besar.";
    $uploadOk = 0;
}

// Cek format file
if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
    echo "Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
    $uploadOk = 0;
}

// Jika $uploadOk adalah 0, berarti file tidak diunggah
if ($uploadOk == 0) {
    echo "File tidak diunggah.";
} else {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        // Simpan data ke database
        $photoName = basename($_FILES["photo"]["name"]); // Simpan nama file ke variabel
        
        $sql = "INSERT INTO login (nip, nama, passs, role, foto) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssss", $user, $nama, $password, $role, $photoName);
        
        if ($stmt->execute()) {
            echo "Registrasi berhasil.";
        } else {
            echo "Terjadi kesalahan saat menyimpan data.";
        }

        $stmt->close();
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
    }
}

$koneksi->close();
?>
