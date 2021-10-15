<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Local {
    function lang() {
      $data = json_decode(file_get_contents("php://input"));
      $local = !empty($data->local) ? htmlspecialchars($data->local, ENT_QUOTES, 'UTF-8') : NULL;
      $section = !empty($data->section) ? htmlspecialchars($data->section, ENT_QUOTES, 'UTF-8') : NULL;
      $db = new Connect;
      $query = "SELECT loc.id, lang.`local`, loc.`key`, loc.$local AS label FROM languages AS lang
      LEFT OUTER JOIN localize AS loc
      ON loc.local_id = lang.id
      LEFT OUTER JOIN lang_section AS sec
      ON sec.id = loc.section_id
      WHERE lang.`local` = '$local' AND sec.language_section = '$section'";
      $statement = $db->prepare($query);
      try {
        if ($statement->execute()) {
          $localizationData = array();
          while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
            $localizationData[$OutputData['id']]=array(
              'id'=> $OutputData['id'],
              'key'=> $OutputData['key'],
              'label'=> $OutputData['label']
            );
          }
          $localizationData = array_values($localizationData);
          $response = array(
            "status" => "success",
            "error" => false,
            "local" => $local,
            "translations" => $localizationData
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
  return $Local = new Local;
?>