<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  include __DIR__ . '/../../vendor/autoload.php';

  use Dilab\Network\SimpleRequest;
  use Dilab\Network\SimpleResponse;
  use Dilab\Resumable;

  class Upload {
    function uploadSlip() {
      try {
        $request = new SimpleRequest();
        $response = new SimpleResponse();
        $resumable = new Resumable($request, $response);

        $uploads_directory =  __DIR__ . '/../../uploads';
        $slips_directory =  __DIR__ . '/../../uploads/slips';
        $chunk_directory =  __DIR__ . '/../../uploads/slips/temp_chunks';
        $slips_directory =  __DIR__ . '/../../uploads/slips/slips';
        $resumable->tempFolder = $chunk_directory;
        $resumable->uploadFolder = $slips_directory;

        if (!file_exists($uploads_directory)) {
          mkdir($uploads_directory, 0777, true);
        }

        if (!file_exists($slips_directory)) {
          mkdir($slips_directory, 0777, true);
        }
        
        if (!file_exists($chunk_directory)) {
          mkdir($chunk_directory, 0777, true);
        }
              
        if (!file_exists($slips_directory)) {
          mkdir($slips_directory, 0777, true);
        }

        $originalName = $resumable->getOriginalFilename(Resumable::WITHOUT_EXTENSION);
        $slugifiedname = $this->uniqueFileName($originalName);
        $resumable->setFilename($slugifiedname);

        $resumable->process();

        if (true === $resumable->isUploadComplete()) {
          $extension = $resumable->getExtension();
          $filename = $resumable->getFilename();
          $last_filename = $filename . '.' . $extension;

          $this->insertSlipRecord($last_filename);
        }
        else {
          throw new RuntimeException('Not uploaded successfully');
        }
      } catch (RuntimeException $e) {
      }
    }
    function uniqueFileName($originalName) {
      return md5($originalName . microtime());
    }
    function insertSlipRecord($file_name) {
      $db = new Connect;
      $query = "INSERT INTO bank_slips (file_name) VALUES (:file_name)";

      $statement = $db->prepare($query);
      try {
        if (
          $statement->execute( [
            'file_name' => $file_name ]
          )
        ) {
          $last_id = $db->lastInsertId();
          $response = array(
            "status" => "success",
            "error" => false,
            "message" => "File uploaded successfully",
            "id" => $last_id
          );
          http_response_code(200);
          echo json_encode($response);
        }
      } catch (Exception $e) {
        $db->rollback();
        throw $e;  
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add the video file to database."));
      }
    }
  }
  return $Upload = new Upload;
?>