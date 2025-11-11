<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('location: dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  
    <title>Menu login</title>
</head>
<style>
    .back {
    background: #e2e2e2;
    width: 100%;
    position: absolute;
    top: 0;
    bottom: 0;
    }

    .div-center {
    width: 400px;
    height: 400px;
    background-color: #fff;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    max-width: 100%;
    max-height: 100%;
    overflow: auto;
    padding: 1em 2em;
    border-bottom: 2px solid #ccc;
    display: table;
    }

    div.content {
    display: table-cell;
    vertical-align: middle;
    }
</style>
<body>
    <div class="back">
        <div class="div-center">
            <div class="content">
                <h3>Login</h3>
            <hr />
            <form action="register_proses.php" method="post">
                <div class="form-group mb-2">
                    <label for="exampleInputUsername1">Email</label>
                    <input type="username" class="form-control" name="email" id="exampleInputEmail1" placeholder="Email" required>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password" required>
                </div>
                <!-- <label><input type="checkbox">Remember me</label> <br> -->
                <button type="submit" class="btn btn-primary mt-2">Login</button>
                <hr />
                <br>
                <p>Belum punya akun?<a href="menu_register.php"><button type="button" class="btn btn-link">Register</button></a></p>
            </form>
        </div>
        </span>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>