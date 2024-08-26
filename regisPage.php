<form class="row g-3 needs-validation" novalidate action="register.php" method="POST" enctype="multipart/form-data">
    <div class="col-12">
        <label for="yourUsername" class="form-label">NIP</label>
        <div class="input-group has-validation">
            <input type="text" name="nip" class="form-control" id="yourUsername" required>
        </div>
    </div>

    <div class="col-12">
        <label for="yourName" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" id="yourName" required>
    </div>

    <div class="col-12">
        <label for="yourPassword" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="yourPassword" required>
    </div>

    <div class="col-12">
        <label for="yourRole" class="form-label">Role</label>
        <select name="role" class="form-select" id="yourRole" required>
            <option value="admin">Admin</option>
            <option value="member">Member</option>
        </select>
    </div>

    <div class="col-12">
        <label for="photo" class="form-label">Foto</label>
        <input type="file" name="photo" class="form-control" id="photo" required>
    </div>

    <div class="col-12">
        <button class="btn btn-primary w-100" type="submit">Register</button>
    </div>
</form>
