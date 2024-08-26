<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import CSV Data</title>
</head>
<body>
    <h2>Import Data Login dari File CSV</h2>
    <form action="impor_csv.php" method="post" enctype="multipart/form-data">
        <label for="file">Pilih File CSV:</label>
        <input type="file" name="file" id="file" accept=".csv" required>
        <br><br>
        <input type="submit" name="import" value="Import">
    </form>
</body>
</html>
