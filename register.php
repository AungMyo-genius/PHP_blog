<?php
session_start();
require 'config/config.inc.php';
require 'config/common.php';

if($_POST) {

  if(empty($_POST['name']) || empty($_POST['email'])|| empty($_POST['password']) || strlen($_POST["password"]) < 5) {
    if(empty($_POST['name'])) {
      $nameErr = "Plz fill the name";
    }
    if(empty($_POST['email'])) {
      $emailErr = "Plz fill the email";
    }
    if(empty($_POST['password'])) {
      $passErr = "Plz add password";
    }elseif(strlen($_POST["password"]) < 5) {
      $passErr = "Password must be at least 5 character long";
    }

  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
      echo "<script>alert('This email is already registered');window.location.href='login.php';</script>";
    } else {
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES(:name,:email,:password)");
      $result = $stmt->execute(
        array(':name'=>$name, ':email'=>$email,':password'=>$password)
      );
      if($result) {
        echo "<script>alert('Register successful');window.location.href='login.php'</script>";
      }
    }
  }

}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Blog</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register</p>

      <form action="register.php" method="post">
        <input name="_token" type="hidden" value="<?php echo escape($_SESSION['_token']); ?>">
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo empty($_POST['name']) ? '':escape($_POST['name']);?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="far fa-address-card"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($nameErr)? '':'*'.$nameErr;?></p>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo empty($_POST['email']) ? '':escape($_POST['email']);?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($emailErr)? '':'*'.$emailErr;?></p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($passErr)? '':'*'.$passErr;?></p>
        <div class="row">

          <!-- /.col -->
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="login.php" type="button" class="btn btn-default btn-block">Login In</a>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <br />

      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
