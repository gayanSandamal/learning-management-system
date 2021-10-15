<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Locs {
    function GetStates() {
      $data = json_decode(file_get_contents("php://input"));
      $country_id = htmlspecialchars($data->country_id, ENT_QUOTES, 'UTF-8');

      if (empty($country_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "country is undefined."));
      } else {
        $db = new Connect;
        $statement = $db->prepare("SELECT * FROM states WHERE country_id = :country_id ORDER BY state ASC");
        try {
          if (
            $statement->execute(
              [
                'country_id' => $country_id
              ]
            )
          ) {
            $statessData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $statessData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'country_id'=> $OutputData['country_id'],
                'label'=> $OutputData['state']
              );
            }
            $statessData = array_values($statessData);
            $response = array(
              "status" => "success",
              "error" => false,
              "states" => $statessData
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
    function GetDistricts() {
      $data = json_decode(file_get_contents("php://input"));
      $state_id = htmlspecialchars($data->state_id, ENT_QUOTES, 'UTF-8');

      if (empty($state_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "country is undefined."));
      } else {
        $db = new Connect;
        $statement = $db->prepare("SELECT * FROM districts WHERE state_id = :state_id ORDER BY district ASC");
        try {
          if (  
            $statement->execute(
              [
                'state_id' => $state_id
              ]
            )
          ) {
            $statessData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $statessData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'state_id'=> $OutputData['state_id'],
                'label'=> $OutputData['district']
              );
            }
            $statessData = array_values($statessData);
            $response = array(
              "status" => "success",
              "error" => false,
              "districts" => $statessData
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
    function GetCities() {
      $data = json_decode(file_get_contents("php://input"));
      $district_id = htmlspecialchars($data->district_id, ENT_QUOTES, 'UTF-8');

      if (empty($district_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "country is undefined."));
      } else {
        $db = new Connect;
        $statement = $db->prepare("SELECT * FROM cities WHERE district_id = :district_id ORDER BY city ASC");
        try {
          if (  
            $statement->execute(
              [
                'district_id' => $district_id
              ]
            )
          ) {
            $statessData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $statessData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'district_id'=> $OutputData['district_id'],
                'label'=> $OutputData['city']
              );
            }
            $statessData = array_values($statessData);
            $response = array(
              "status" => "success",
              "error" => false,
              "cities" => $statessData
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
    function GetGrades() {
      $db = new Connect;
      $statement = $db->prepare("SELECT * FROM grades");
      $statement->execute();
      $statessData = array();
      while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
        $statessData[$OutputData['id']]=array(
          'id'=> $OutputData['id'],
          'label'=> $OutputData['grade']
        );
      }
      $statessData = array_values($statessData);
      $response = array(
        "status" => "success",
        "error" => false,
        "grades" => $statessData
      );
      http_response_code(200);
      echo json_encode($response);
    }
  }
  return $Locs = new Locs;
?>