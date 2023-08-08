<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require 'assets/config.php';
require 'assets/database_class.php';
$db = Database::getInstance();
$pdo = $db->getConnection();
if (isset($_SESSION['user_id'])) {
  $records = $pdo->prepare('SELECT email, password FROM user WHERE user_id = :user_id');
  $records->bindParam(':user_id', $_SESSION['user_id']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $user = null;

  if (count(array($results)) > 0) {
    $user = $results;
  }
  else {
    header("Location: ../app/auth/login");
  }
}
else {
  header("Location: ../app/auth/login");
}
?>
