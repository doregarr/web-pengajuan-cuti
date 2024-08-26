<?php
include("koneksi.php");
$nik = $_POST['nik'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$nohp = $_POST['nohp'];
$tgllahir = $_POST['tgllahir'];
$asal = $_POST['asal'];
$jenkel = $_POST['jenkel'];
$jalur = $_POST['jalur'];

$sql ="update pendaftaran set nama='$nama',
email='$email',
nohp='$nohp',
tgllahir='$tgllahir',
asalsekolah='$asal',
jenkel='$jenkel',
jalur='$jalur'where nik='$nik' ";
$proses = mysqli_query($koneksi, $sql);
if($proses) {
    header("location:tabeldata.php");
}