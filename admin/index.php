<?php


session_start();
require '../config/config.inc.php';
require '../config/common.php';

if(empty($_SESSION['user_id']) && empty($SESSION['logged_in'])) {
  header('location: login.php');
}


 ?>







<?php include 'header.php'; ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Listings</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <div>
                    <a href="add.php" type="submit" class="btn btn-success">New Blog Post</a>
                  </div>
                  <br>
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <?php
                  if(empty($_POST['search'])) {
                    if(!empty($_GET['pageno'])) {
                      $pageno = $_GET['pageno'];
                    } else {
                      $pageno = 1;
                    }
                    $numOfrecs = 5;
                    $offset = ($pageno-1) * $numOfrecs;
                    $stmt = $pdo->prepare("SELECT * from posts ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchALL();
                    $total_pages = ceil(count($rawResult)/ $numOfrecs);

                    $stmt = $pdo->prepare("SELECT * from posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
                    $stmt->execute();
                    $result = $stmt->fetchALL();
                  } else {
                    $searchKey = $_POST['search'];
                    if(!empty($_GET['pageno'])) {
                      $pageno = $_GET['pageno'];
                    } else {
                      $pageno = 1;
                    }
                    $numOfrecs = 1;
                    $offset = ($pageno-1) * $numOfrecs;
                    $stmt = $pdo->prepare("SELECT * from posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchALL();
                    $total_pages = ceil(count($rawResult)/ $numOfrecs);

                    $stmt = $pdo->prepare("SELECT * from posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                    $stmt->execute();
                    $result = $stmt->fetchALL();
                  }
                  ?>
                  <tbody>
                    <?php if($result) {
                      $i = 1;
                      foreach($result as $value) {
                    ?>
                    <tr>
                      <td><?php echo $i;?></td>
                      <td><?php echo escape($value['title']);?></td>
                      <td><?php echo escape(substr($value['content'],0,60));?></td>
                      <td class="btn-group">
                      <div class="container"><a href="edit.php?id=<?php echo escape($value['id'])?>" type="submit" class="btn btn-warning">Edit</a></div>
                      <div class="container"><a href="delete.php?id=<?php echo escape($value['id'])?>"
                        onclick="return confirm('Are you want to delete this item.')"
                        type="submit" class="btn btn-danger">Delete</a></div>

                      </td>
                    </tr>
                    <?php
                      $i++; }}
                    ?>


                  </tbody>
                </table><br />
                <nav aria-label="Page navigation example" style="float:left">
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
                </nav>
              </div>
              <!-- /.card-body -->

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
