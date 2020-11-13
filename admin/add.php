<?php

require '../config/config.inc.php';
session_start();

if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}

if($_POST) {
  $file = 'images/'.$_FILES['image']['name'];
  $imgInfo = strtolower(pathinfo($file ,PATHINFO_EXTENSION));

  if($imgInfo == 'png' && $imgInfo == 'jpg' && $imgInfo == 'jpeg') {
    echo "<script>alert('wrong image type')</script>";
  } else{
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $author_id = $_SESSION['user_id'];

    move_uploaded_file($_FILES['image']['tmp_name'], $file);
    $stmt = $pdo->prepare("INSERT INTO posts (title,content,image,author_id) VALUES(:title,:content,:image,:author_id)");
    $result = $stmt->execute(
      array(':title'=>$title, ':content'=>$content,':image'=>$image,':author_id'=>$author_id)
    );
    if($result) {
      echo "<script>alert('Successfully added');window.location.href='index.php'</script>";
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
              <form role="form" action="add.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Content</label>
                    <textarea name="content" rows="8" cols="70" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="image" class="form-control-file" id="exampleInputFile">

                      </div>

                    </div>
                  </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="index.php" class="btn btn-warning" type="button">Back</a>
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
