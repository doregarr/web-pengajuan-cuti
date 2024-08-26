<?php 
$host ="localhost";
$user ="root";
$pass ="";
$db ="dbpkl";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if ($koneksi){

}
else{
    echo "Koneksi Gagal";
}
?>