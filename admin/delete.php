<?php
require '../config/config.inc.php';


$stmt = $pdo->prepare("DELETE from posts WHERE id=".$_GET['id']);

$stmt->execute();
header("Location: index.php");


 ?>
