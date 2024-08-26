<?php
include("koneksi.php");

if (isset($_POST['import'])) {
    // Cek apakah file sudah diupload
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $csvFile = $_FILES['file']['tmp_name'];

        // Buka file CSV
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            // Loop untuk membaca setiap baris CSV
            $rowNumber = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rowNumber++;

                // Validasi apakah jumlah kolom sesuai yang diharapkan
                if (count($data) != 7) {
                    echo "Baris $rowNumber: Jumlah kolom tidak sesuai: " . count($data) . "<br>";
                    continue;
                }

                // Ambil data dari baris CSV
                $nama = isset($data[0]) ? $data[0] : NULL;
                $nip = isset($data[1]) ? $data[1] : NULL;
                $pangkat = isset($data[2]) ? $data[2] : NULL;
                $jabatan = isset($data[3]) ? $data[3] : NULL;
                $password = isset($data[4]) ? $data[4] : NULL;
                $foto = isset($data[5]) ? $data[5] : NULL; // Kosongkan jika tidak ada foto
                $role = isset($data[6]) ? $data[6] : NULL;

                // Debug: Tampilkan data yang akan diimpor
                echo "Baris $rowNumber: Nama: $nama, NIP: $nip, Pangkat: $pangkat, Jabatan: $jabatan, Password: $password, Foto: $foto, Role: $role<br>";

                // Lanjutkan hanya jika jabatan dan password tidak kosong
                if (empty($jabatan) || empty($password)) {
                    echo "Baris $rowNumber: Jabatan atau password kosong, melewati baris.<br>";
                    continue;
                }

                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Query untuk memasukkan data ke database
                $sql = "INSERT INTO login (nama, nip, pangkat, jabatan, passs, foto, role) VALUES (?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        nama = VALUES(nama),
                        pangkat = VALUES(pangkat),
                        jabatan = VALUES(jabatan),
                        passs = VALUES(passs),
                        foto = VALUES(foto),
                        role = VALUES(role)";
                $stmt = $koneksi->prepare($sql);
                if ($stmt === FALSE) {
                    die("Gagal menyiapkan query: " . $koneksi->error);
                }

                $stmt->bind_param("sssssss", $nama, $nip, $pangkat, $jabatan, $hashedPassword, $foto, $role);

                if ($stmt->execute()) {
                    echo "Baris $rowNumber: Data untuk NIP $nip berhasil diimpor.<br>";
                } else {
                    echo "Baris $rowNumber: Gagal mengimpor data untuk NIP $nip: " . $stmt->error . "<br>";
                }
            }

            fclose($handle);
            echo "Data selesai diproses.";
        } else {
            echo "Gagal membuka file CSV.";
        }
    } else {
        echo "Gagal mengupload file.";
    }
}
?>
