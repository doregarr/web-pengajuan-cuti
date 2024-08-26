
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>FORM</title>
  </head>
  <body>
  <div class="card">
  <div class="card-header">
    Form Tambah Data Mahasiswa
  </div>
  <div class="card-body">
  <form action="simpan.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label" >NIM</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="txtnim">
  
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label" >Nama</label>
    <input type="text" class="form-control" id="exampleInputPassword1"  name="txtnama">
  </div>
  <div class="mb-3">
  <select class="form-select" aria-label="Default select example" name="txtjenkel">
  <option selected>Pilih Jenis Kelamin</option>
  <option value="Laki-Laki">Laki-Laki</option>
  <option value="Perempuan">Perempuan</option>
  
</select>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label" >Tempat Tanggal Lahir</label>
    <input type="text" class="form-control" id="exampleInputPassword1"  name="txtlahir">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label" >NO HP</label>
    <input type="text" class="form-control" id="exampleInputPassword1"  name="txthp">
  </div>
 
  <button type="submit" class="btn btn-primary">Tambah</button>
  <a type="button" class="btn btn-warning" href="tampil2.php" >Tampil</a>
</form>
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
</html><!-- <html>
    <head>

    </head>
    <body>
        <h2>Form Tambah Mahasiswa</h2>
        <form action = "simpan.php" method="POST">
            NIM  : <br>
            <input type="text" name="txtnim"><br>
            Nama  : <br>
            <input type="text" name="txtnama"><br>
            Jenis Kelamin : <br>
            <select name="txtjenkel">
                <option value ="Laki-Laki">Laki-Laki</option>
                <option value ="Perempuan">Perempuan</option>
            </select><br>
            Tempat Tanggal Lahir : <br>
            <input type="text" name="txtlahir"><br>
            No HP : <br>
            <input type="text" name="txthp"><br>
            <button type="submit">Simpan</button>
        </form>
    </body>
</html> -->