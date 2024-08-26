<?php
include("koneksi.php");
$nik = $_GET['x'];


$sql ="delete from pendaftaran where nik='$nik'";

$proses = mysqli_query($koneksi, $sql);
if($proses) {
    header("location:tabeldata.php");
}