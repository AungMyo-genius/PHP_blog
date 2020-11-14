<?php
session_start();
require 'config/config.inc.php';
require 'config/common.php';
if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}



 ?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Start your jounary here</title>
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
  <?php
  if(empty($_GET['pageno'])) {
    $pageno = 1 ;
  } else {
    $pageno = $_GET['pageno'];
    }

  $numOfrecs = 2;
  $offset = ($pageno -1) * $numOfrecs;
  $stmt = $pdo->prepare("SELECT * from posts ORDER BY id DESC");
  $stmt->execute();
  $rawResult = $stmt->fetchALL();

  $total_pages = ceil(count($rawResult)/ $numOfrecs);

  $stmt = $pdo->prepare("SELECT * from posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
  $stmt->execute();
  $result = $stmt->fetchALL();
  ?>
  <div class="wrapper">
    <div class="content-wrapper" style="margin-left:0px;">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 style="text-align:center;"> My Blog
              <span style="float:right; font-size:1rem;"><a href="logout.php" class="btn btn-primary">Logout</a></span>

            </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


<section class="content">
      <div class="row">
        <?php $i =1 ; if($result) { foreach($result as $value)  { ?>
        <div class="col-md-6">
          <!-- Box Comment -->
          <div class="card card-widget">
            <div class="card-header">
              <div style="float:none; text-align:center" class="card-title">
                <h4><?php echo escape($value['title']);?></h4>
              </div>


            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <a href="blogdetail.php?id=<?php echo escape($value['id'])?>">
                <img style="height:400px !important;" class="img-fluid pad" src="admin/images/<?php echo escape($value['image']);?>"/></a>
            </div>
          </div>
        </div>
        <?php $i++; } }?>
  </div>
  <div>
  <nav aria-label="Page navigation example" style="float:right !important; margin-right:100px !important">
    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
      <li class="page-item <?php if($pageno <=1){ echo 'disabled';} ?>">
        <a class="page-link" href="<?php if($pageno <=1){echo '#';}else{echo "?pageno=".($pageno-1);}?>">Previous</a>
      </li>
      <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
      <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';}?>">
        <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);}?>">Next</a>
      </li>
      <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a></li>
    </ul>
  </nav><br /><br />
</div>
</section>
<footer class="main-footer" style="margin-left:0px;">
<div class="float-right d-none d-sm-block">
  <b> <?php if($_SESSION['role'] == 1){echo "<a href='admin/index.php' type='button' class='btn btn-default'>Admin Panel</a>";} ?></b>
</div>
<strong>Copyright &copy; <?php echo date('Y');?> <a href="">Actulized</a>.</strong> All rights
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
