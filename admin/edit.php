<?php

require '../config/config.inc.php';
session_start();

if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}
if($_SESSION['role'] != 1){
    header('location: login.php');
}

if($_POST) {

  if(empty($_POST['title']) || empty($_POST['content'])) {
    if(empty($_POST['title'])) {
      $titleErr = "Plz fill the title";
    }
    if(empty($_POST['content'])) {
      $contentErr = "Plz fill the content";
    }
  }
  else {
    $title = $_POST['title'];
    $content = $_POST['content'];
    if($_FILES['image']['name']) {
      $file = 'images/'.$_FILES['image']['name'];
      $imgInfo = pathinfo($file ,PATHINFO_EXTENSION);

      if($imgInfo != 'png' && $imgInfo != 'jpg' && $imgInfo != 'jpeg') {
        echo "<script>alert('wrong image type')</script>";
      } else{
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
        $stmt = $pdo->prepare("UPDATE posts SET title=:title, content=:content,image=:image,author_id=:author_id WHERE id=".$_POST['id']);
        $result = $stmt->execute(
          array(':title'=>$title, ':content'=>$content,':image'=>$image,':author_id'=>$_SESSION['user_id'])
        );
        if($result) {
          echo "<script>alert('Successfully updated');window.location.href='index.php'</script>";
        }
      }
    } else {
      $stmt = $pdo->prepare("UPDATE posts SET title=:title, content=:content,author_id=:author_id WHERE id=".$_POST['id']);
      $result = $stmt->execute(
        array(':title'=>$title, ':content'=>$content,':author_id'=>$_SESSION['user_id'])
      );
      if($result) {
        echo "<script>alert('Successfully updated');window.location.href='index.php'</script>";
      }
    }

  }


}

?>







<?php include 'header.php'; ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
        <?php
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id =".$_GET['id']);
            $stmt->execute();
            $output = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $output['id']?>">
                <div class="form-group">
                    <label for="">Title</label><p style="color:red;"><?php echo empty($titleErr) ? '': '*'.$titleErr;?></p>
                    <input type="text" name="title" id="" class="form-control" value="<?php echo $output['title']?>">
                </div>
                <div class="form-group">
                    <label for="">Content</label><p style="color:red;"><?php echo empty($contentErr) ? '': '*'.$contentErr;?></p>
                    <textarea name="content" id="" cols="30" rows="10" class="form-control"><?php echo $output['content']?></textarea>
                </div>
                <div class="form-group">
                    <label for="">Image</label><br>
                    <img src="images/<?php echo $output['image']?>" alt="" width="200" >
                    <input type="file" name="image" id="" class="form-control-file mt-2">
                </div>
                <div class="form-group">
                    <input type="submit" value="Update" id="" class="btn btn-primary">
                    <a href="index.php" type="button" class="btn btn-warning">Back</a>
                </div>
            </form>
        </div>

        </div>
        <!-- /.card -->

        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'footer.php'?>
