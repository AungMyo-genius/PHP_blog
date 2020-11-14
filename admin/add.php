<?php
session_start();
require '../config/config.inc.php';
require '../config/common.php';


if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}

if($_POST) {

  if(empty($_POST['title']) || empty($_POST['content'])|| empty($_FILES['image'])) {
    if(empty($_POST['title'])) {
      $titleErr = "Plz fill the title";
    }
    if(empty($_POST['content'])) {
      $contentErr = "Plz fill the content";
    }
    if(empty($_FILES['image'])) {
      $imageErr = "Plz add image";
    }
  }
  else {
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
                <input name="_token" type="hidden" value="<?php echo escape($_SESSION['_token']); ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Title</label><p style="color:red;"><?php echo empty($titleErr) ? '': '*'.$titleErr;?></p>
                    <input type="text" class="form-control" name="title" id="title">
                  </div>
                  <div class="form-group">
                    <label>Content</label><p style="color:red;"><?php echo empty($contentErr) ? '': '*'.$contentErr;?></p>
                    <textarea name="content" rows="8" cols="70" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <label>File input</label>
                    <p style="color:red;"><?php echo empty($imageErr) ? '': '*'.$imageErr;?></p>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="image" class="form-control-file">

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
