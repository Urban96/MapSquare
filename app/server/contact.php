<?php

//Contact Form
if(CONTACT_EMAIL) {

  if (!empty($_POST['email']))  {

    $to = "daniel.urban02@upol.cz";
    $subject = "MapSquare.io - message";
    $txt = $_POST['name'] . " --- " . $_POST['message'];

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: ' . $_POST['email'] . "\r\n";

    $mail_success = mail($to,$subject,$txt,$headers);

    if ($mail_success) {
      $status = "OK";
      $message = 'Thank You for Your message.';
    }
    else {
      $status = "ERROR";
      $message = 'E-mail could not be sent, please contact us at info@mapsquare.io.';
    }

  } else {
    $status = "ERROR";
    $message = "Unable to send an email, please contact us at info@mapsquare.io.";
  }


  echo json_encode(['status'=>$status, 'message'=>$message]);

}

?>
