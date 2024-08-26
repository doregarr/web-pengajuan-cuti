
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!-- CDN Font awesome  -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <title>Hello, world!</title>
  </head>
  <body>
    
    <?php
include("koneksi.php");
$sql="select * from mahasiswa";
$proses=mysqli_query($koneksi, $sql);
?>
  <div class="card">
  <div class="card-header">
    Tabel Biodata Mahasiswa
  </div>
  <div class="card-body">
  <table class="table">
  <thead>
    <tr class="table-primary">
   
      <th scope="col">NIM</th>
      <th scope="col">Nama</th>
      <th scope="col">Jenis Kelamin</th>
      <th scope="col">Tempat/Tanggal Lahir</th>
      <th scope="col">NO HP</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php
while ($isi = mysqli_fetch_assoc($proses)):?>
    <tr>
    <td><?php echo $isi['nim']; ?></td>
    <td><?php echo $isi['nama']; ?></td>
    <td><?php echo $isi['jenkel']; ?></td>
    <td><?php echo $isi['lahir']; ?></td>
    <td><?php echo $isi['hp']; ?></td>
    <td>
        <a type="button" class="btn btn-primary"  href="ubah.php?x=<?php echo $isi['nim']; ?>" ><i class="fa-solid fa-pen-to-square"></i></a>
        <a type="button" class="btn btn-danger" href="hapus.php?x=<?php echo $isi['nim']; ?>"><i class="fa-solid fa-trash"></i></a></td>
</tr>
<?php
endwhile;
?>
    </tr>
    
  </tbody>
</table>
<a type="button" class="btn btn-success" href="form.php" ><i class="fa-solid fa-user-plus"></i></a>
<a type="button" class="btn btn-danger" href="logout.php" ><i class="fa-solid fa-right-from-bracket"></i></a>
  </div>
</div>
   

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
