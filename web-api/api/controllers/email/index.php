<?php
  class Email {
    function protocol() {
      return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    }
    function domain() {
      return $this->protocol() . "://$_SERVER[HTTP_HOST]";
    }
    function siteName() {
      return "$_SERVER[HTTP_HOST]";
    }
    function sendVerificationEmail($email_obj) {
      $from = !empty($email_obj['from']) ? htmlspecialchars($email_obj['from'], ENT_QUOTES, 'UTF-8') : '';
      $to = !empty($email_obj['to']) ? htmlspecialchars($email_obj['to'], ENT_QUOTES, 'UTF-8') : '';
      $name = !empty($email_obj['name']) ? htmlspecialchars($email_obj['name'], ENT_QUOTES, 'UTF-8') : '';
      $subject = !empty($email_obj['subject']) ? htmlspecialchars($email_obj['subject'], ENT_QUOTES, 'UTF-8') : '';
      $token = !empty($email_obj['token']) ? htmlspecialchars($email_obj['token'], ENT_QUOTES, 'UTF-8') : '';

      $Validations = new Validations;
      $host = $this->siteName();

      if (empty($subject)) {
        http_response_code(400);
        echo json_encode(array("message" => "subject is undefined."));
      } else if (empty($name)) {
        http_response_code(400);
        echo json_encode(array("message" => "name is undefined."));
      } else if (empty($from)) {
        http_response_code(400);
        echo json_encode(array("message" => "from email is undefined."));
      } else if (!$Validations->isEmail($from)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($from)->message));
      } else if (empty($to)) {
        http_response_code(400);
        echo json_encode(array("message" => "to email is undefined."));
      } else if (!$Validations->isEmail($to)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($from)->message));
      } else {
        $headers = "From: no_reply@" . $host . "\n";
        $headers .= "Reply-To: no_reply@" . $host . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1";
        $message = '<html><body>';
        $message .= '<h3 style=\"color:#4b61b1;\">Dear '.$name.',</h3>';
        $message .= '<p style=\"color:#091850;font-size:14px;\">Please use below link to verify your account in ' . $host . '</p>';
        $message .= '<p>' . $this->domain() . '/verify?email='.$to.'&token='.$token.'</p>';
        $message .= '<p style=\"color:#ea2828;\">Please ignore if you are not aware of this email</p>';
        $message .= '<p>Thanks,<br>www.' . $host . '</p>';
        $message .= '</body></html>';
        if (@mail($to, $subject, $message, $headers)) {
          return true;
        } else {
          return false;
        }
      }
    }
    function sendResetPasswordEmail($email_obj) {
      $from = !empty($email_obj['from']) ? htmlspecialchars($email_obj['from'], ENT_QUOTES, 'UTF-8') : '';
      $to = !empty($email_obj['to']) ? htmlspecialchars($email_obj['to'], ENT_QUOTES, 'UTF-8') : '';
      $name = !empty($email_obj['name']) ? htmlspecialchars($email_obj['name'], ENT_QUOTES, 'UTF-8') : '';
      $subject = !empty($email_obj['subject']) ? htmlspecialchars($email_obj['subject'], ENT_QUOTES, 'UTF-8') : '';
      $token = !empty($email_obj['token']) ? htmlspecialchars($email_obj['token'], ENT_QUOTES, 'UTF-8') : '';

      $Validations = new Validations;
      $host = $this->siteName();

      if (empty($subject)) {
        http_response_code(400);
        echo json_encode(array("message" => "subject is undefined."));
      } else if (empty($name)) {
        http_response_code(400);
        echo json_encode(array("message" => "name is undefined."));
      } else if (empty($from)) {
        http_response_code(400);
        echo json_encode(array("message" => "from email is undefined."));
      } else if (!$Validations->isEmail($from)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($from)->message));
      } else if (empty($to)) {
        http_response_code(400);
        echo json_encode(array("message" => "to email is undefined."));
      } else if (!$Validations->isEmail($to)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($from)->message));
      } else {
        $headers = "From: no_reply@" . $host . "\n";
        $headers .= "Reply-To: no_reply@" . $host . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1";
        $message = '<html><body>';
        $message .= '<h3 style=\"color:#4b61b1;\">Dear '.$name.',</h3>';
        $message .= '<p style=\"color:#091850;font-size:14px;\">Please use below link to reset your account password in ' . $host . '</p>';
        $message .= '<p>' . $this->domain() . '/reset-password?email='.$to.'&token='.$token.'</p>';
        $message .= '<p style=\"color:#ea2828;\">Please ignore if you are not aware of this email</p>';
        $message .= '<p>Thanks,<br>www.' . $host . '</p>';
        $message .= '</body></html>';
        if (@mail($to, $subject, $message, $headers)) {
          return true;
        } else {
          return false;
        }
      }
    }
    function sendApprovalEmail($email_obj) {
      $from = !empty($email_obj['from']) ? htmlspecialchars($email_obj['from'], ENT_QUOTES, 'UTF-8') : '';
      $to = !empty($email_obj['to']) ? htmlspecialchars($email_obj['to'], ENT_QUOTES, 'UTF-8') : '';
      $name = !empty($email_obj['name']) ? htmlspecialchars($email_obj['name'], ENT_QUOTES, 'UTF-8') : '';
      $subject = !empty($email_obj['subject']) ? htmlspecialchars($email_obj['subject'], ENT_QUOTES, 'UTF-8') : '';
      $cat_name = !empty($email_obj['cat_name']) ? htmlspecialchars($email_obj['cat_name'], ENT_QUOTES, 'UTF-8') : '';
      $approval = !empty($email_obj['approval']) ? htmlspecialchars($email_obj['approval'], ENT_QUOTES, 'UTF-8') : '';
      $reason = !empty($email_obj['reason']) ? htmlspecialchars($email_obj['reason'], ENT_QUOTES, 'UTF-8') : '';
      $invoice = !empty($email_obj['invoice']) ? htmlspecialchars($email_obj['invoice'], ENT_QUOTES, 'UTF-8') : '';

      $Validations = new Validations;
      $host = $this->siteName();

      if (empty($subject)) {
        http_response_code(400);
        echo json_encode(array("message" => "subject is undefined."));
      } else if (empty($name)) {
        http_response_code(400);
        echo json_encode(array("message" => "name is undefined."));
      } else if (empty($from)) {
        http_response_code(400);
        echo json_encode(array("message" => "from email is undefined."));
      } else if (!$Validations->isEmail($from)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($from)->message));
      } else if (empty($to)) {
        http_response_code(400);
        echo json_encode(array("message" => "to email is undefined."));
      } else if (!$Validations->isEmail($to)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($from)->message));
      } else {
        
        $msg = '';
        if ($approval == 0) {
          $msg = "Your enrollment for <strong>" . $cat_name . "</strong> is pending...";
        } else if ($approval == 1) {
          $msg = "Your enrollment for <strong>" . $cat_name . "</strong> is approved";
        } else if ($approval == 2) {
          $msg = 'Your enrollment for <strong>' . $cat_name . '</strong> is rejected due to "' . $reason . '"';
        }

        $headers = "From: no_reply@" . $host . "\n";
        $headers .= "Reply-To: no_reply@" . $host . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1";
        $message = '<html><body>';
        $message .= '<h3 style=\"color:#4b61b1;\">Dear '.$name.',</h3>';
        $message .= '<p style=\"color:#091850;font-size:14px;\">' . $msg . '</p>';
        $message .= '<p style=\"color:#dd2b2b;font-size:14px;\">Invoice no. ' . $invoice . '</p>';
        $message .= '<p>Thanks,<br>www.' . $host . '</p>';
        $message .= '</body></html>';
        if (@mail($to, $subject, $message, $headers)) {
          return true;
        } else {
          return false;
        }
      }
    }
  }
  return $Email = new Email;
?>