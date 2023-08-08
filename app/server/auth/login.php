<?php

if (isset($_SESSION['user_id'])) {
  echo json_encode(['status'=>'OK', 'message'=>'Logged In']);
  die;
}

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

if (!empty($_POST['email']) && !empty($_POST['password'])) {
  $status = "ERROR";
  $message = "Unable to login. Please contact our support.";

  $records = $pdo->prepare('SELECT * FROM user WHERE email = :email');
  $records->bindParam(':email', $_POST['email']);
  $records->execute();
  $rows = $records->rowCount();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $message = '';

  if ($rows > 0) {
    if ($results['active'] == 1) {
      if (password_verify($_POST['password'], $results['password'])) {
        $_SESSION['user_id'] = $results['user_id'];
        $status = "OK";
        $message = "Successfully logged in.";
      }
      else {
        $message = 'Wrong e-mail address or password.';
      }
    }
    else {
      $message = 'The user account was not activated yet. Check your email inbox.';
    }

  } else {
    $message = 'Wrong e-mail address or password.';
  }

  echo json_encode(['status'=>$status, 'message'=>$message]);
}

?>
