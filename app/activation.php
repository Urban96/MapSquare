<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
  }

  require 'server/assets/config.php';
  require 'server/assets/database_class.php';

  //Database connection
  $db = Database::getInstance();
  $pdo = $db->getConnection();

  $code = $_GET['id'];


  $sql = 'SELECT count(*) FROM activation_code WHERE code = ?';
  $result = $pdo->prepare($sql);
  $result->execute([$code]);
  $number_of_rows = (int)$result->fetchColumn();

  if ($number_of_rows != 1) {
    die("<center>ERROR: Couldn't activate account. Contact us at info@mapsquare.io</center>");
  }

  $activate = $pdo->prepare('SELECT * FROM activation_code WHERE code = :code');
  $activate->bindParam(':code', $code);
  $activate->execute();
  $row = $activate->fetch(PDO::FETCH_ASSOC);

  if ($row['used'] == 1) {
    header('Location: login');
  }
  else {
    $email = $row['email'];

    $sql1 = "UPDATE activation_code SET used = 1 WHERE code = :code";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindParam(':code', $code);
    $stmt1->execute();

    $sql = "UPDATE user SET active = 1 WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    header('Location: login');
  }

?>
