<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Event {
    function Sucscribe() {
      $data = json_decode(file_get_contents("php://input"));
      $firstname = htmlspecialchars($data->firstname, ENT_QUOTES, 'UTF-8');
      $lastname = htmlspecialchars($data->lastname, ENT_QUOTES, 'UTF-8');
      $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
      $phone = htmlspecialchars($data->phone, ENT_QUOTES, 'UTF-8');
      $nic = htmlspecialchars($data->nic, ENT_QUOTES, 'UTF-8');
      $batch = htmlspecialchars($data->batch, ENT_QUOTES, 'UTF-8');
      $class_list = !empty($data->class_list) ? $data->class_list : [];
      $fee = htmlspecialchars($data->fee, ENT_QUOTES, 'UTF-8');
      $slip_id = htmlspecialchars($data->slip_id, ENT_QUOTES, 'UTF-8');
      $year = htmlspecialchars($data->year, ENT_QUOTES, 'UTF-8');
      $month = htmlspecialchars($data->month, ENT_QUOTES, 'UTF-8');
      
      $Validations = new Validations;

      if (empty($firstname)) {
        http_response_code(400);
        echo json_encode(array("message" => "firstname is undefined."));
      } else if (!$Validations->isName($firstname)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isName($firstname)->message));
      } else if (empty($lastname)) {
        http_response_code(400);
        echo json_encode(array("message" => "lastname is undefined."));
      } else if (!$Validations->isName($lastname)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isName($lastname)->message));
      } else if (empty($email)) {
        http_response_code(400);
        echo json_encode(array("message" => "email is undefined."));
      } else if (!$Validations->isEmail($email)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($email)->message));
      } else if (empty($phone)) {
        http_response_code(400);
        echo json_encode(array("message" => "phone is undefined."));
      } else if (!$Validations->isPhone($phone)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isPhone($phone)->message));
      } else if (empty($nic)) {
        http_response_code(400);
        echo json_encode(array("message" => "phone is undefined."));
      } else if (!$Validations->isNic($nic)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isNic($nic)->message));
      } else if (empty($batch)) {
        http_response_code(400);
        echo json_encode(array("message" => "batch is undefined."));
      } else if (!$Validations->isInt($batch)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($batch)->message));
      } else if (empty($fee)) {
        http_response_code(400);
        echo json_encode(array("message" => "fee is undefined."));
      } else if (empty($slip_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "slip is undefined."));
      } else if (empty($year)) {
        http_response_code(400);
        echo json_encode(array("message" => "year is undefined."));
      } else if (!$Validations->isInt($year)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($year)->message));
      } else if (empty($month)) {
        http_response_code(400);
        echo json_encode(array("message" => "month is undefined."));
      } else if (!$Validations->isInt($month)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($month)->message));
      } else if (empty(count($class_list) > 0)) {
        http_response_code(400);
        echo json_encode(array("message" => "classes are empty."));
      } else {
        $db = new Connect;
        $statement = $db->prepare("INSERT INTO subscriptions (firstname, lastname, email, phone, nic, year, month, batch, fee, slip_id)
        VALUES (:firstname, :lastname, :email, :phone, :nic, :year, :month, :batch, :fee, :slip_id)");
        try {
          if (
            $statement->execute(
              [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'nic' => $nic,
                'year' => $year,
                'month' => $month,
                'batch' => $batch,
                'fee' => $fee,
                'slip_id' => $slip_id
              ]
            )
          ) {
            $last_id = $db->lastInsertId();
            $reference_no = date('Ymd') . $last_id;
            $this->referClasses($db, $last_id, $class_list, $reference_no, $slip_id);
          }
        } catch (Exception $e) {
          http_response_code(400);
          $db->rollback();
          throw $e;
        }
      }
    }
    function referClasses($db, $last_id, $class_list, $reference_no, $slip_id) {
      $count = 0;
      foreach ($class_list as $class_item) {
        $count++;
        $class_table = '';
        if ($class_item->id == 1) {
          // theory
          $class_table = 'theory_class';
        } else if ($class_item->id == 2) {
          // revision
          $class_table = 'revision_class';
        }
        $query = "INSERT INTO $class_table (sub_id, class_id, fee)
        VALUES ('$last_id', '$class_item->id', '$class_item->fee')";
        $statement = $db->prepare($query);
        $statement->execute();

        $refer_slip_query = "UPDATE bank_slips SET referred = 1 WHERE id = '$slip_id'";
        $refer_slip_statement = $db->prepare($refer_slip_query);
        $refer_slip_statement->execute();
        
        $refer_slip_query = "UPDATE subscriptions SET reference_no = '$reference_no' WHERE id = '$last_id'";
        $refer_slip_statement = $db->prepare($refer_slip_query);
        $refer_slip_statement->execute();

        if ($count == count($class_list)) {
          $response = array(
            "status" => "success",
            "error" => false,
            "ref" => $reference_no
          );
          http_response_code(200);
          echo json_encode($response);
        }
      }
    }
  }
  return $Event = new Event;
?>