<?php
require '../config/config.inc.php';


$stmt = $pdo->prepare("DELETE from users WHERE id=".$_GET['id']);

$stmt->execute();
header("Location: user_list.php");


 ?>
