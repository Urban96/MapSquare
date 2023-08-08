<?php

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

if(PASSWORD_RECOVER) {

  if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
  }


  if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {

    $parameter = $_POST['pass_string'];


    if (($_POST['password']) == ($_POST['confirm_password'])) {

      $user = $pdo->prepare('SELECT * FROM lost_password WHERE pass_string = :pass_string AND exp_date >= NOW() AND used = "0"');
      $user->bindParam(':pass_string', $parameter);
      $user->execute();
      $row = $user->fetch(PDO::FETCH_ASSOC);

      $user_id = $row['user_id'];

      if ($user_id == "") {
        $status = 'ERROR';
        $message = 'Code was already used.';
      } else {
        $sql = "UPDATE user SET password = :password WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);

        $sql1 = "UPDATE lost_password SET used = 1 WHERE pass_string = :pass_string";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->bindParam(':pass_string', $parameter);

        if (($stmt->execute()) && ($stmt1->execute())) {
          $status = 'OK';
          $message = 'Password successfully changed. You can <a href="login" class="fw-bold">Log In</a> now.';

        } else {
          $status = 'ERROR';
          $message = 'Something went wrong. Contact us at info@mapsquare.io';
        }
      }

    }
    else {
      $status = 'ERROR';
      $message = 'Passwords does not match.';
    }

  }

} else {
  $status = "ERROR";
  $message = "Unable to recover password.";
}

echo json_encode(['status'=>$status, 'message'=>$message]);

?>
