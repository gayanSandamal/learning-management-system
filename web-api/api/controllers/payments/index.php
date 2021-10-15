<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Payments {
    function filteredPayments() {
      $data = json_decode(file_get_contents("php://input"));
      $filter_by = htmlspecialchars($data->filter_by, ENT_QUOTES, 'UTF-8');
      $filter_keyword = htmlspecialchars($data->filter_keyword, ENT_QUOTES, 'UTF-8');
      $year = htmlspecialchars($data->year, ENT_QUOTES, 'UTF-8');
      $month = htmlspecialchars($data->month, ENT_QUOTES, 'UTF-8');
      $batch = htmlspecialchars($data->batch, ENT_QUOTES, 'UTF-8');
      $per_page = htmlspecialchars($data->per_page, ENT_QUOTES, 'UTF-8');
      $offset = htmlspecialchars($data->offset, ENT_QUOTES, 'UTF-8');
      $class_type = htmlspecialchars($data->class_type, ENT_QUOTES, 'UTF-8');
      $state = htmlspecialchars($data->state, ENT_QUOTES, 'UTF-8');
      $export = $data->export;

      $db = new Connect;
      $query = '';
      if ($class_type == 1) {
        $query = "SELECT s.id, s.firstname, s.lastname, s.email, s.phone, s.nic, s.fee, s.reference_no, b.file_name FROM subscriptions AS s
        LEFT JOIN theory_class AS t
        ON s.id = t.sub_id
        LEFT JOIN bank_slips AS b
        ON s.slip_id = b.id
        WHERE '$filter_by' LIKE '%$filter_keyword%'
        AND year = '$year'
        AND month = '$month'
        AND batch = '$batch'
        AND t.class_id = 1
        AND s.approval = $state
        ORDER BY s.id DESC
        LIMIT $per_page
        OFFSET $offset";
      } else if ($class_type == 2) {
        $query = "SELECT s.id, s.firstname, s.lastname, s.email, s.phone, s.nic, s.fee, s.reference_no, b.file_name FROM subscriptions AS s
        LEFT JOIN revision_class AS r
        ON s.id = r.sub_id
        LEFT JOIN bank_slips AS b
        ON s.slip_id = b.id
        WHERE '$filter_by' LIKE '%$filter_keyword%'
        AND year = '$year'
        AND month = '$month'
        AND batch = '$batch'
        AND r.class_id = 2
        AND s.approval = $state
        ORDER BY s.id DESC
        LIMIT $per_page
        OFFSET $offset";
      } else if ($class_type == 3) {
        $query = "SELECT s.id, s.firstname, s.lastname, s.email, s.phone, s.nic, s.fee, s.reference_no, b.file_name FROM subscriptions AS s
        LEFT JOIN theory_class AS t
        ON s.id = t.sub_id
        LEFT JOIN revision_class AS r
        ON s.id = r.sub_id
        LEFT JOIN bank_slips AS b
        ON s.slip_id = b.id
        WHERE '$filter_by' LIKE '%$filter_keyword%'
        AND year = '$year'
        AND month = '$month'
        AND batch = '$batch'
        AND t.class_id = 1
        OR r.class_id = 2
        AND s.approval = $state
        ORDER BY s.id DESC
        LIMIT $per_page
        OFFSET $offset";
      }

      $statement = $db->prepare($query);
      $statement->execute();
      $paymentsData = array();
      while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
        $paymentsData[$OutputData['id']]=array(
          'id'=> $OutputData['id'],
          'firstname'=> $OutputData['firstname'],
          'lastname'=> $OutputData['lastname'],
          'email'=> $OutputData['email'],
          'phone'=> $OutputData['phone'],
          'nic'=> $OutputData['nic'],
          'fee'=> $OutputData['fee'],
          'reference_no'=> $OutputData['reference_no'],
          'file_name'=> $OutputData['file_name']
        );
      }
      $paymentsData = array_values($paymentsData);

      $payment_query = "SELECT COUNT(*) AS count
      FROM subscriptions WHERE
      '$filter_by' LIKE '%$filter_keyword%' AND
      year = '$year' AND
      month = '$month' AND
      batch = '$batch'";
      $payment_count_statement = $db->prepare($payment_query);
      $payment_count_statement->execute();
      $payment_count_row = $payment_count_statement->fetch();
      $row_payment_count_row = $payment_count_row['count'];

      if ($export == true) {
        self::csv_gen($paymentsData, $row_payment_count_row);
      } else {
        $payments_data = (object) [
          'count'=> $row_payment_count_row,
          'records' => $paymentsData
        ];
        return json_encode($payments_data);
      }
    }
    static function csv_gen($paymentsData) {
      // output headers so that the file is downloaded rather than displayed
      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=data.csv');
      // create a file pointer connected to the output stream
      $output = fopen('php://memory', 'w');

      // output the column headings
      fputcsv($output, array('id', 'firstname', 'lastname', 'email', 'phone', 'nic', 'fee', 'reference_no'));

      // loop over the rows, outputting them
      foreach($paymentsData as $row) {
        // generate csv lines from the inner arrays
        fputcsv($output, $row);
      }
      // reset the file pointer to the start of the file
      fseek($output, 0);
      // tell the browser it's going to be a csv file
      header('Content-Type: application/csv');
      // tell the browser we want to save it instead of displaying it
      header('Content-Disposition: attachment; filename="data.csv";');
      // make php send the generated csv lines to the browser
      fpassthru($output);
    }
    static function array2csv($array) {
      if (count($array) == 0) {
        return null;
      }
      ob_start();
      echo 2;
      $df = fopen("php://memory", 'w');
      fputcsv($df, array_keys(reset($array)));
      foreach ($array as $row) {
        fputcsv($df, $row);
      }
      echo 3;
      fclose($df);
      return ob_get_clean();
    }
    static function download_send_headers($filename) {
      // disable caching
      $now = gmdate("D, d M Y H:i:s");
      header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
      header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
      header("Last-Modified: {$now} GMT");
  
      // force download  
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
  
      // disposition / encoding on response body
      header("Content-Disposition: attachment;filename={$filename}");
      header("Content-Transfer-Encoding: binary");
    }
    function approve() {
      $data = json_decode(file_get_contents("php://input"));
      $reference_no = htmlspecialchars($data->ref, ENT_QUOTES, 'UTF-8');
      $db = new Connect;
      $query = "UPDATE subscriptions SET approval = 1 WHERE reference_no = :reference_no";
      $statement = $db->prepare($query);
      try {
        if (
          $statement->execute(
            [
              'reference_no' => $reference_no
            ]
          )
        ) {
          $response = array(
            "status" => "success",
            "error" => false,
            "message" => "Successfully approved",
            "ref" => $reference_no
          );
          http_response_code(200);
          echo json_encode($response);
        }
      } catch (Exception $e) {
        http_response_code(400);
        $db->rollback();
        throw $e;
      }
    }
    function reject() {
      $data = json_decode(file_get_contents("php://input"));
      $reference_no = htmlspecialchars($data->ref, ENT_QUOTES, 'UTF-8');
      $db = new Connect;
      $query = "UPDATE subscriptions SET approval = 0 WHERE reference_no = :reference_no";
      $statement = $db->prepare($query);
      try {
        if (
          $statement->execute(
            [
              'reference_no' => $reference_no
            ]
          )
        ) {
          $response = array(
            "status" => "success",
            "error" => false,
            "message" => "Successfully rejected",
            "ref" => $reference_no
          );
          http_response_code(200);
          echo json_encode($response);
        }
      } catch (Exception $e) {
        http_response_code(400);
        $db->rollback();
        throw $e;
      }
    }
  }
  return $Payments = new Payments;
?>