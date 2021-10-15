<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Dashboard {
    function getTotal() {
      $data = json_decode(file_get_contents("php://input"));
      $id = !empty($data->id) ? htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $category_id = !empty($data->category_id) ? htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8') : NULL;
      $db = new Connect;

      $is_allowed_query = "SELECT COUNT(*) AS is_allowed FROM useres WHERE id = '$id' AND (roleId = 1025 OR roleId = 1026)";

      $is_allowed_statement = $db->prepare($is_allowed_query);
      $is_allowed_statement->execute();
      $is_allowed_row = $is_allowed_statement->fetch();
      $is_allowed = json_encode($is_allowed_row["is_allowed"]);
      $is_allowed = (int)$is_allowed;
      if ($is_allowed == 1) {
     
        $query = "SELECT * FROM categories WHERE id = :category_id AND post_type_id = :post_type_id";
        $statement = $db->prepare($query);
        try {
          if (
            $statement->execute([
              'category_id' => $category_id,
              'post_type_id' => $post_type_id
            ])
          ) {
            $categoriesData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $categoriesData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'post_type_id'=> $OutputData['post_type_id'],
                'parent_category_id'=> $OutputData['parent_category_id'],
                'name'=> $OutputData['name'],
                'slug'=> $OutputData['slug'],
                'last_updated'=> $OutputData['last_updated'],
                'thumbnail'=> $OutputData['thumbnail'],
                'image'=> $OutputData['image'],
                'display_order'=> $OutputData['display_order'],
                'has_child'=> $OutputData['has_child'],
                'is_show'=> $OutputData['is_show'],
              );
            }
            $categoriesData = array_values($categoriesData);
            $response = array(
              "status" => "success",
              "error" => false,
              "category" => $categoriesData[0]
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
  }
  return $Dashboard = new Dashboard;
?>