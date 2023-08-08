<?php

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

if (!empty($_POST['email']) && !empty($_POST['password'])) {
  $status = "ERROR";
  $message = "Unable to create account. Please contact our support.";

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $status = "ERROR";
    $message = "Invalid e-mail address.";
  }
  else {
    $sql1 = "SELECT * FROM user WHERE email = :email";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindParam(':email', $_POST['email']);
    $stmt1->execute();
    $row1 = $results1 = $stmt1->fetch(PDO::FETCH_ASSOC);

      if($row1)
      {
          $status = "ERROR";
          $message = 'User with these credentials already exists. <a href="lostPassword" class="fw-bold">Forgot Your password?</a>';
      }
      else {

        while(true){
          $user_id = generateRandomString();
          $sql_user = "SELECT * FROM user WHERE user_id = :user_id";
          $stmt_user = $pdo->prepare($sql_user);
          $stmt_user->bindParam(':user_id', $user_id);
          $stmt_user->execute();
          $row_user = $results_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

          if(!$row_user) {
            break;
          }
        }

        if(EMAIL_VERIFICATION) {
          $sql = "INSERT INTO user (user_id, email, password, level, cdate, active) VALUES (:user_id, :email, :password, 1, NOW(), 0)";
        } else {
          $sql = "INSERT INTO user (user_id, email, password, level, cdate, active) VALUES (:user_id, :email, :password, 1, NOW(), 1)";
        }
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':email', $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);
        if ($stmt->execute()) {

          if(EMAIL_VERIFICATION) {
            $sql = "INSERT INTO activation_code (user_id, email, code, used, cdate) VALUES (:user_id, :email, :code, 0, NOW())";
          } else {
            $sql = "INSERT INTO activation_code (user_id, email, code, used, cdate) VALUES (:user_id, :email, :code, 1, NOW())";
          }
          $stmt = $pdo->prepare($sql);

          while(true){
            $activation_code = generateRandomString();
            $sql_code = "SELECT * FROM activation_code WHERE code = :code";
            $stmt_code = $pdo->prepare($sql_code);
            $stmt_code->bindParam(':code', $activation_code);
            $stmt_code->execute();
            $row_code = $results_code = $stmt_code->fetch(PDO::FETCH_ASSOC);

            if(!$row_code) {
              break;
            }
          }

          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':email', $_POST['email']);
          $stmt->bindParam(':code', $activation_code);
          if ($stmt->execute()) {

            if(EMAIL_VERIFICATION) {
              $to = $_POST['email'];
              $subject = "MapSquare - Account activation";
              $txt = 'Hello, click this link to activate your account - https://mapsquare.io/app/activation.php?id=' . $activation_code;

              $headers = "MIME-Version: 1.0" . "\r\n";
              $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

              $headers .= 'From: <info@mapsquare.io>' . "\r\n";

              $mail_success = mail($to,$subject,$txt,$headers);

              if ($mail_success) {
                $status = "OK";
                $message = 'Your account was successfully created. You can <a href="login" class="fw-bold">Log In</a> now.';
              }
              else {
                $status = "ERROR";
                $message = 'The activation e-mail cannot be sent, contact us at info@mapsquare.io';
              }
            } else {
              $status = "OK";
              $message = 'Your account was successfully created. You can <a href="login" class="fw-bold">Log In</a> now.';
            }

          }

        }

      }

  }

  echo json_encode(['status'=>$status, 'message'=>$message]);

}

?>
