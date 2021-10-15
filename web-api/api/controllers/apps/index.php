<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Downloads {
    function getFeaturingDownloads() {
      $db = new Connect;
      $query = "SELECT
      a.id id,
      a.app_type app_type,
      a.download_url url,
      a.version version,
      a.feature feature,
      a.`timestamp` `timestamp`,
      t.type type,
      t.line1 line1,
      t.line2 line2
      FROM app_list a
      LEFT JOIN app_types t
      ON a.app_type = t.id
      WHERE a.feature = 1";
      $statement = $db->prepare($query);
      try {
        if ($statement->execute()) {
          $downloadsData = array();
          while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
            $downloadsData[]=array(
              'id'=> $OutputData['id'],
              'app_type'=> $OutputData['app_type'],
              'url'=> $OutputData['url'],
              'version'=> $OutputData['version'],
              'feature'=> $OutputData['feature'],
              'timestamp'=> $OutputData['timestamp'],
              'type'=> $OutputData['type'],
              'line1'=> $OutputData['line1'],
              'line2'=> $OutputData['line2']
            );
          }
          $downloadsData = array_values($downloadsData);
          $response = array(
            "status" => "success",
            "error" => false,
            "downloads" => $downloadsData
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
  return $Downloads = new Downloads;
?>