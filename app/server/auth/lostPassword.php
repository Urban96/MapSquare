<?php

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

if(PASSWORD_RECOVER) {

  if (!empty($_POST['email']))  {
    $sql = 'SELECT count(*) FROM user WHERE email = ?';
    $result = $pdo->prepare($sql);
    $result->execute([$_POST['email']]);
    $number_of_rows = (int)$result->fetchColumn();

    $message = '';

    if ($number_of_rows == 1) {
      $records = $pdo->prepare('SELECT * FROM user WHERE email = :email');
      $records->bindParam(':email', $_POST['email']);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $email = $results['email'];
      $user_id = $results['user_id'];

      $sql = 'SELECT count(*) FROM lost_password WHERE user_id = ? AND exp_date >= NOW() AND used = "0"';
      $result = $pdo->prepare($sql);
      $result->execute([$user_id]);
      $number_of_rows_pass_string = (int)$result->fetchColumn();

      if ($number_of_rows_pass_string >= 1) {
        $status = "ERROR";
        $message = 'A code has already been generated, a new one cannot be generated at this time. Check your e-mail inbox.';
      }
      else {
        while(true){
          $recovery_string = generateRandomString();
          $sql_rs = "SELECT * FROM lost_password WHERE pass_string = :pass_string";
          $stmt_rs = $pdo->prepare($sql_rs);
          $stmt_rs->bindParam(':pass_string', $sql_rs);
          $stmt_rs->execute();
          $row_rs = $results_rs = $stmt_rs->fetch(PDO::FETCH_ASSOC);

          if(!$row_rs) {
            break;
          }
        }

        $records1 = $pdo->prepare('INSERT INTO lost_password (user_id, pass_string, cdate, exp_date) VALUES (:user_id, :pass_string, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP + INTERVAL 1 HOUR)');
        $records1->bindParam(':user_id', $user_id);
        $records1->bindParam(':pass_string', $recovery_string);
        $records1->execute();
        $results1 = $records1->fetch(PDO::FETCH_ASSOC);

        $to = $_POST['email'];
        $subject = "MapSquare - password recovery";
        $txt = 'Hello, click this link to reset your password - ' . URL_ADDRESS .'app/auth/recover.php?id=' . $recovery_string;

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: <info@mapsquare.io>' . "\r\n";

        $mail_success = mail($to,$subject,$txt,$headers);

        if ($mail_success) {
          $status = "OK";
          $message = 'We have sent you an e-mail with instructions.';
        }
        else {
          $status = "ERROR";
          $message = 'E-mail could not be sent, please contact us at info@mapsquare.io';
        }
      }

    } else if ($number_of_rows != 1) {
      $status = "ERROR";
      $message = 'E-mail address does not exist.';
    }
    else {
      $status = "ERROR";
      $message = 'E-mail could not be sent, please contact us at info@mapsquare.io';
    }

  }

} else {
  $status = "ERROR";
  $message = "Unable to recover password.";
}

echo json_encode(['status'=>$status, 'message'=>$message]);

?>
