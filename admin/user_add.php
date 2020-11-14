<?php

require '../config/config.inc.php';
session_start();

if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}


if(!empty($_POST)) {
  $name = $_POST['name'];
  $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
  $role = $_POST['role'];
  $email = $_POST['email'];

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

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user) {
        echo "<script>alert('This email is already exited plz use another email');window.location.href='user_list.php';</script>";
      }

       $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES(:name,:email,:password,:role)");
       $result = $stmt->execute(
         array(':name'=>$name, ':email'=>$email,':password'=>$password,':role'=>$role)
       );
       if($result) {
         echo "<script>alert('New user successfully added');window.location.href='user_list.php'</script>";
       }
    }

}




 ?>







<?php include 'header.php'; ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <form role="form" action="user_add.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <p style="color:red;"><?php echo empty($nameErr)? '':'*'.$nameErr;?></p>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email">
                    <p style="color:red;"><?php echo empty($emailErr)? '':'*'.$emailErr;?></p>
                  </div>
                  <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" value="">
                    <p style="color:red;"><?php echo empty($passErr)? '':'*'.$passErr;?></p>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="admin" value="1">
                    <label class="form-check-label" for="admin">
                      Admin
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="user" value="0" checked>
                    <label class="form-check-label" for="user">
                      User
                    </label>
                  </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="user_list.php" class="btn btn-warning" type="button">Back</a>
                </div>
              </form>

            </div>
            <!-- /.card -->


            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include 'footer.php'?>
