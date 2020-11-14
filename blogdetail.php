<?php
session_start();
require 'config/config.inc.php';
if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}
$stmt = $pdo->prepare("SELECT * from posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

$blogId = $_GET['id'];
if($_POST) {
  if(empty($_POST['comment'])) {
    $cmtErr = "Do not press enter without filling comment!";
  } else {
    $content = $_POST['comment'];
    $author_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO comments (content,author_id,post_id) VALUES(:content,:author_id,:post_id)");
    $result = $stmt->execute(
      array(':content'=>$content,':author_id'=>$author_id,':post_id'=>$blogId)
    );

    if($result) {
      header('location: blogdetail.php?id='.$blogId);
    }
  }

}

//comment fetchALL
$stmtCmt = $pdo->prepare("SELECT * from comments WHERE post_id=".$blogId);
$stmtCmt->execute();
$cmResult = $stmtCmt->fetchAll();





 ?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
  <div>

    <section class="content">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 style="text-align:center;">Self-actualization</h1>
          </div>
        </div>
      </div>
    </section>


    <section class="content">
      <div class="container">
      <div class="row">
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="card card-widget">
            <div class="card-header">
              <div style="float:none; text-align:center" class="card-title">
                <h4><?php echo $result[0]['title'];?><span style="float:left">
                  <a href="logout.php" type="button" class="btn btn-primary">Logout</a></span>
                  <span style="float:right;"><a href="index.php" class="btn btn-primary">Back </a></span>
                </h4>
              </div>

            </div>

            <div class="card-body">
              <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image'];?>" alt="Photo">

              <p style="font-size:2rem;"><?php echo $result[0]['content'];?></p>
              <!-- <button type="button" class="btn btn-default btn-sm"><i class="far fa-thumbs-up"></i> Like</button>
              <span class="float-right text-muted">127 likes - 3 comments</span> -->
            </div>


              <p style="color:red;"><?php echo empty($cmtErr)? '':'*'.$cmtErr;?></p>
              <div class="card-footer card-comments">
                <h4>Comment:</h4>
                <?php if($cmResult) {
                  foreach($cmResult as $value) {
                    $username_id = $value['author_id'];
                    //fetch username for comment
                    $stmtau = $pdo->prepare("SELECT * from users WHERE id=".$username_id);
                    $stmtau->execute();
                    $auResult = $stmtau->fetchAll();?>
              <div class="card-comment">


                <div class="comment-text" style="margin-left:0px !important">
                  <span class="username">
                    <?php echo $auResult[0]['name']?>
                    <span class="text-muted float-right"><?php echo $value['created_at']?> </span>
                  </span>
                    <?php echo $value['content']?>
                </div>
                <!-- /.comment-text -->
              </div>
              <!-- /.card-comment -->
            <?php } } ?>
              <!-- /.card-comment -->
            </div>
            <!-- /.card-footer -->
            <div class="card-footer">
              <form action="" method="post">

                <div class="img-push">
                  <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                </div>
              </form>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      </div>
</section>

<footer class="main-footer" style="margin-left:0px;">
  <div class="float-right d-none d-sm-block">
    <b> <?php if($_SESSION['role'] == 1){echo "<a href='admin/index.php' type='button' class='btn btn-default'>Admin Panel</a>";} ?></b>
  </div>
<strong>Copyright &copy; <?php echo date('Y')?> <a href="">Actulized</a>.</strong> All rights
reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>



<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
