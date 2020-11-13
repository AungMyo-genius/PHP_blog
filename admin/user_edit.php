<?php

require '../config/config.inc.php';
session_start();

if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}

if(!empty($_POST)) {
  $name=$_POST['name'];
  $email=$_POST['email'];
  $password=$_POST['password'];
  $role=$_POST['role'];
  $id = $_POST['id'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND id!=:id");
  $stmt->bindValue(':email',$email);
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if($user) {
    echo "<script>alert('This email is already exited plz use another email');window.location.href='user_list.php';</script>";
  }

  else {
  $stmt = $pdo->prepare("UPDATE users SET name=:name, email=:email,password=:password,role=:role WHERE id=".$_POST['id']);
  $result = $stmt->execute(
    array(':name'=>$name, ':email'=>$email,':password'=>$password,':role'=>$role)
  );
  if($result) {
    echo "<script>alert('successfully edited');window.location.href='user_list.php'</script>";
  }
}

}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();



 ?>







<?php include 'header.php'; ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <form role="form" action="user_edit.php" method="POST">
                <div class="card-body">
                  <input type="hidden" class="form-control" name="id" value="<?php echo $result[0]['id']?>">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name"  value="<?php echo $result[0]['name']?>">
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $result[0]['email']?>">

                  </div>
                  <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="text" class="form-control" name="password" id="password" value="<?php echo $result[0]['password'];?>">

                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="admin" value="1" <?php if($result[0]['role']==1) echo 'checked';?>>
                    <label class="form-check-label" for="admin">
                      Admin
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="user" value="0" <?php if($result[0]['role']==0) echo 'checked';?>>
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
