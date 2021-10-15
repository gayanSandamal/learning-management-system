<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class PostType {
    function getAllPostTypes() {
      $db = new Connect;
      $statement = $db->prepare("SELECT * FROM post_types");
      try {
        if ($statement->execute()) {
          $postTypesData = array();
          while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
            $postTypesData[$OutputData['id']]=array(
              'id'=> $OutputData['id'],
              'name'=> $OutputData['name'],
              'slug'=> $OutputData['slug'],
              'last_updated'=> $OutputData['last_updated']
            );
          }
          $postTypesData = array_values($postTypesData);
          $response = array(
            "status" => "success",
            "error" => false,
            "post_types" => $postTypesData
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
    function getCats() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_type_slug = !empty($data->post_type_slug) ? htmlspecialchars($data->post_type_slug, ENT_QUOTES, 'UTF-8') : NULL;
      $order_by = !empty($data->order_by) ? htmlspecialchars($data->order_by, ENT_QUOTES, 'UTF-8') : 'id';
      $order = !empty($data->order) ? htmlspecialchars($data->order, ENT_QUOTES, 'UTF-8') : 'DESC';
      $parent = !empty($data->parent) ? htmlspecialchars($data->parent, ENT_QUOTES, 'UTF-8') : 'with_parent';
      $parent_id = !empty($data->parent_id) ? htmlspecialchars($data->parent_id, ENT_QUOTES, 'UTF-8') : NULL;
      
      $per_page = !empty($data->per_page) ? htmlspecialchars($data->per_page, ENT_QUOTES, 'UTF-8') : 5000;
      $offset = !empty($data->offset) ? htmlspecialchars($data->offset, ENT_QUOTES, 'UTF-8') : 0;
      $search_keyword = !empty($data->search_keyword) ? htmlspecialchars($data->search_keyword, ENT_QUOTES, 'UTF-8') : NULL;

      $db = new Connect;
      $query = "SELECT * FROM categories";
      if(!empty($post_type_id)) {
        $query = $query . ' ' . "WHERE post_type_id = '$post_type_id'";
      } else if(!empty($post_type_slug)) {
        $query = $query . ' ' . "WHERE post_type_slug = '$post_type_slug'";
      } else if(!empty($post_type_id) && !empty($post_type_slug)) {
        $query = $query . ' ' . "WHERE post_type_id = '$post_type_id' AND post_type_slug = '$post_type_slug'";
      }
      if (!empty($parent)) {
        if ($parent == 'no_parent') {
          $query = $query . ' ' . "AND parent_category_id IS NULL";
        }
      }
      if (!empty($parent_id)) {
        $query = $query . ' ' . "AND parent_category_id = $parent_id";
      }
      $query = $query . ' ' . "AND name LIKE '%$search_keyword%' ORDER BY $order_by $order LIMIT $per_page OFFSET $offset";
      $statement = $db->prepare($query);
      try {
        if ($statement->execute()) {
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
            );
          }
          $categoriesData = array_values($categoriesData);

          $categories_count_query = "SELECT COUNT(*) AS record_count FROM categories WHERE name LIKE '%$search_keyword%'";
          $categories_count_statement = $db->prepare($categories_count_query);
          $categories_count_statement->execute();
          $categories_count_row = $categories_count_statement->fetch();

          $response = array(
            "status" => "success",
            "error" => false,
            "cats" => $categoriesData,
            'record_count'=> $categories_count_row['record_count']
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
    function addCats() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $name = !empty($data->name) ? htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') : NULL;
      $slug = !empty($data->slug) ? htmlspecialchars($data->slug, ENT_QUOTES, 'UTF-8') : NULL;
      $parent_id = !empty($data->parent_id) ? htmlspecialchars($data->parent_id, ENT_QUOTES, 'UTF-8') : NULL;
      $is_show = !empty($data->is_show) ? htmlspecialchars($data->is_show, ENT_QUOTES, 'UTF-8') : 0;

      $Validations = new Validations;
      
      if ($Validations->tokenValidate()) {
        $db = new Connect;

        $get_max_display_order_no_parent_query = "SELECT MAX(display_order) AS max_order FROM categories WHERE parent_category_id IS NULL";
        $get_max_display_order_no_parent_statement = $db->prepare($get_max_display_order_no_parent_query);
        $get_max_display_order_no_parent_statement->execute();
        $get_max_display_order_no_parent_row = $get_max_display_order_no_parent_statement->fetch();
        $max_order_no_parent = $get_max_display_order_no_parent_row["max_order"];
        $max_order_no_parent = $max_order_no_parent + 1;

        $query = "INSERT INTO categories (post_type_id, name, slug, display_order, is_show) VALUES ($post_type_id, '$name', '$slug', $max_order_no_parent, $is_show)";
        if(!empty($parent_id)) {
          // get max order number begins
          $get_max_display_order_parent_query = "SELECT MAX(display_order) AS max_order FROM categories WHERE parent_category_id = $parent_id";
          $get_max_display_order_parent_statement = $db->prepare($get_max_display_order_parent_query);
          $get_max_display_order_parent_statement->execute();
          $get_max_display_order_parent_row = $get_max_display_order_parent_statement->fetch();
          $max_order = $get_max_display_order_parent_row["max_order"];
          $max_order = $max_order + 1;
          // get max order number ends
          // set slug begins
          $slug_query = "SELECT slug FROM categories WHERE id = $parent_id";
          $slug_statement = $db->prepare($slug_query);
          $slug_statement->execute();
          $slug_row = $slug_statement->fetch();
          $parent_slug = $slug_row["slug"];
          $child_slug = $parent_slug . '-' . $slug;
          // set slug ends

          $query = "INSERT INTO categories (post_type_id, parent_category_id, name, slug, display_order, is_show) VALUES ($post_type_id, $parent_id, '$name', '$child_slug', $max_order, $is_show)";
          $set_has_child_query = "UPDATE categories SET has_child = 1 WHERE id = $parent_id";
          $set_has_child__statement = $db->prepare($set_has_child_query);
          $set_has_child__statement->execute();
        }
        $statement = $db->prepare($query);
        try {
          if ($statement->execute()) {
            $response = array(
              "status" => "success",
              "error" => false
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
    function getCat() {
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
    function UpdateCat() {
      $data = json_decode(file_get_contents("php://input"));
      $id = !empty($data->id) ? htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $name = !empty($data->name) ? htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') : NULL;
      $slug = !empty($data->slug) ? htmlspecialchars($data->slug, ENT_QUOTES, 'UTF-8') : NULL;
      $parent_id = !empty($data->parent_id) ? htmlspecialchars($data->parent_id, ENT_QUOTES, 'UTF-8') : NULL;
      $is_show = !empty($data->is_show) ? htmlspecialchars($data->is_show, ENT_QUOTES, 'UTF-8') : 0;
      $category_id = !empty($data->category_id) ? htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8') : 0;

      $Validations = new Validations;
      if ($Validations->tokenValidate()) {
        $db = new Connect;

        $is_allowed_query = "SELECT COUNT(*) AS is_allowed FROM useres WHERE id = '$id' AND (roleId = 1025 OR roleId = 1026)";

        $is_allowed_statement = $db->prepare($is_allowed_query);
        $is_allowed_statement->execute();
        $is_allowed_row = $is_allowed_statement->fetch();
        $is_allowed = json_encode($is_allowed_row["is_allowed"]);
        $is_allowed = (int)$is_allowed;
        if ($is_allowed == 1) {
          $query = "UPDATE categories SET post_type_id = $post_type_id, name = '$name', slug = '$slug', is_show = $is_show WHERE id = $category_id";
          if(!empty($parent_id)) {
            // set slug begins
            $slug_query = "SELECT slug FROM categories WHERE id = $parent_id";
            $slug_statement = $db->prepare($slug_query);
            $slug_statement->execute();
            $slug_row = $slug_statement->fetch();
            $parent_slug = $slug_row["slug"];
            $child_slug = $parent_slug . '-' . $slug;
            // set slug ends

            $query = "UPDATE categories SET post_type_id = $post_type_id, parent_category_id = $parent_id, name = '$name', slug = '$child_slug', is_show = $is_show WHERE id = $category_id";
            $set_has_child_query = "UPDATE categories SET has_child = 1 WHERE id = $parent_id";
            $set_has_child__statement = $db->prepare($set_has_child_query);
            $set_has_child__statement->execute();
          }
          $statement = $db->prepare($query);
          try {
            if ($statement->execute()) {
              $response = array(
                "status" => "success",
                "error" => false
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
    function getUsersByRole() {
      $data = json_decode(file_get_contents("php://input"));
      $roleId = !empty($data->roleId) ? htmlspecialchars($data->roleId, ENT_QUOTES, 'UTF-8') : NULL;
      $db = new Connect;
     
      $query = "SELECT
      u.id,
      u.username,
      u.firstname,
      u.lastname,
      u.email,
      u.is_email_verified,
      u.roleId,
      r.label AS roleName
      FROM useres AS u
      LEFT JOIN roles AS r
      ON u.roleId = r.roleId WHERE u.roleId = :roleId";
      $statement = $db->prepare($query);
      try {
        if (
          $statement->execute([
            'roleId' => $roleId
          ])
        ) {
          $userData = array();
          while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
            $userData[$OutputData['id']]=array(
              'id' => $OutputData['id'],
              'username' => $OutputData['username'],
              'firstname' => $OutputData['firstname'],
              'lastname' => $OutputData['lastname'],
              'email' => $OutputData['email'],
              'is_email_verified' => $OutputData['is_email_verified'],
              'roleId' => $OutputData['roleId'],
              'roleName' => $OutputData['roleName'],
            );
          }
          $userData = array_values($userData);
          $response = array(
            "status" => "success",
            "error" => false,
            "users" => $userData
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
    function addPosts() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $category_id = !empty($data->category_id) ? htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8') : NULL;
      $name = !empty($data->name) ? htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') : '';
      $slug = !empty($data->slug) ? htmlspecialchars($data->slug, ENT_QUOTES, 'UTF-8') : '';
      $desc = !empty($data->desc) ? htmlspecialchars($data->desc, ENT_QUOTES, 'UTF-8') : '';
      $thumbnail = !empty($data->thumbnail) ? htmlspecialchars($data->thumbnail, ENT_QUOTES, 'UTF-8') : '';
      $image = !empty($data->image) ? htmlspecialchars($data->image, ENT_QUOTES, 'UTF-8') : '';
      $start = !empty($data->start) ? htmlspecialchars($data->start, ENT_QUOTES, 'UTF-8') : date("Y-m-d H:i:s");
      $end = !empty($data->end) ? htmlspecialchars($data->end, ENT_QUOTES, 'UTF-8') : '3000-12-12 00:00:00';
      $assigned_date = !empty($data->assigned_date) ? htmlspecialchars($data->assigned_date, ENT_QUOTES, 'UTF-8') : NULL;
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $assignee_id = !empty($data->assignee_id) ? htmlspecialchars($data->assignee_id, ENT_QUOTES, 'UTF-8') : '';
      $attempts = !empty($data->attempts) ? htmlspecialchars($data->attempts, ENT_QUOTES, 'UTF-8') : NULL;
      $fee = !empty($data->fee) ? htmlspecialchars($data->fee, ENT_QUOTES, 'UTF-8') : '';
      $videos = !empty($data->videos) ? $data->videos : [];

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "INSERT INTO posts (post_type_id, category_id, name, slug, `desc`, thumbnail, image, start, end, user_id, assignee_id, fee, assigned_date) VALUES ($post_type_id, $category_id, '$name', '$slug', '$desc', '$thumbnail', '$image', '$start', '$end', $user_id, $assignee_id, $fee, '$assigned_date')";
        $statement = $db->prepare($query);
        try {
          if ($statement->execute()) {
            $last_id = $db->lastInsertId();
            $this->referVideos($db, $last_id, $videos, $attempts);
            $response = array(
              "status" => "success",
              "error" => false,
              "message" => "Post is successfully published"
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
    function referVideos($db, $last_id, $videos, $attempts) {
      foreach ($videos as $video) {
        $query = "INSERT INTO videos
        (title, src, duration, post_id, display_order, service, given_attempts) VALUES
        ('$video->title', '$video->src', $video->duration, $last_id, $video->display_order, '$video->service', $attempts)";
        $statement = $db->prepare($query);
        $statement->execute();
      }
    }
    function updateVideos($db, $id, $videos, $attempts) {
      // get already enrolled video attempts begins
      $video_ids = [];
      foreach ($videos as $video) {
        if (!empty($video->id)) {
          array_push($video_ids, $video->id);
        }
      }
      $video_ids = join(',', $video_ids);

      $enrolled_videos = array();
      if(!empty($video_ids)) {
        $get_enrolled_videos_query = "SELECT id, post_id, video_id, student_id, attempts FROM video_attempts WHERE video_id IN ($video_ids)";
        $get_enrolled_videos_statement = $db->prepare($get_enrolled_videos_query);
        $get_enrolled_videos_statement->execute();
        while($OutputData=$get_enrolled_videos_statement->fetch(PDO::FETCH_ASSOC)){
          $enrolled_videos[]=array(
            'id' => $OutputData['id'],
            'post_id' => $OutputData['post_id'],
            'video_id' => $OutputData['video_id'],
            'student_id' => $OutputData['student_id'],
            'attempts' => $OutputData['attempts']
          );
        }
      }
      // get already enrolled video attempts ends
      if (count($enrolled_videos) > 0) {
        $attempt_ids = [];
        foreach ($enrolled_videos as $attempt) {
          array_push($attempt_ids, $attempt['id']);
        }
        $attempt_ids = join(',', $attempt_ids);
        
        // delete already enrolled attempts begins
        $delete_enrolled_videos_query = "DELETE FROM video_attempts WHERE id IN ($attempt_ids)";
        $delete_enrolled_videos_statement = $db->prepare($delete_enrolled_videos_query);
        $delete_enrolled_videos_statement->execute();
        // delete already enrolled attempts ends
      }

      $del_query = "DELETE FROM videos WHERE post_id = $id";
      $del_statement = $db->prepare($del_query);
      $new_video_ids = [];
      try {
        if ($del_statement->execute()) {
          foreach ($videos as $video) {
            if (empty($video->deleted)) {
              $query = "INSERT INTO videos
              (title, src, duration, post_id, display_order, service, given_attempts) VALUES
              ('$video->title', '$video->src', $video->duration, $id, $video->display_order, '$video->service', $attempts)";
              $statement = $db->prepare($query);
              $statement->execute();
              $last_video_id = $db->lastInsertId();
              array_push($new_video_ids, $last_video_id);
            }
          }
          if (count($enrolled_videos) > 0) {
            $added_post_id = null;
            $added_student_id = null;
            $added_attempts = null;
            $added_new_video_ids = []; 
            foreach ($enrolled_videos as $enrolled_video) {
              $video_post_id = $enrolled_video['post_id'];
              $video_student_id = $enrolled_video['student_id'];
              foreach ($new_video_ids as $new_video_id) {
                if (($added_post_id != $video_post_id) || ($added_student_id != $video_student_id) || ($added_attempts != $attempts) || empty(in_array($new_video_id, $added_new_video_ids))) {
                  $query = "INSERT INTO video_attempts
                  (post_id, video_id, student_id, attempts) VALUES
                  ($video_post_id, $new_video_id, $video_student_id, $attempts)";
                  $statement = $db->prepare($query);
                  $statement->execute();
                  $added_post_id = $video_post_id;
                  $added_student_id = $video_student_id;
                  $added_attempts = $attempts;
                  array_push($added_new_video_ids, $new_video_id);
                }
              }
            }
          }
        }
      } catch (Exception $e) {
        http_response_code(400);
        $db->rollback();
        throw $e;
      }
    }
    function getCustomFields() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $location = !empty($data->location) ? htmlspecialchars($data->location, ENT_QUOTES, 'UTF-8') : NULL;

      $db = new Connect;
      $query = "SELECT * FROM custom_fields WHERE post_type_id = :post_type_id AND appear_on = :location";
      $statement = $db->prepare($query);
      try {
        if ($statement->execute([
          'post_type_id' => $post_type_id,
          'location' => $location
        ])) {
          $customFieldsData = array();
          while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
            $customFieldsData[$OutputData['id']]=array(
              'id'=> $OutputData['id'],
              'post_type_id'=> $OutputData['post_type_id'],
              'name'=> $OutputData['name'],
              'slug'=> $OutputData['slug'],
              'type'=> $OutputData['type'],
              'value'=> $OutputData['value'],
              'mandatory'=> $OutputData['mandatory'],
              'note'=> $OutputData['note'],
              'placeholder'=> $OutputData['placeholder'],
              'position'=> $OutputData['position'],
              'display_order'=> $OutputData['display_order'],
              'last_updated'=> $OutputData['last_updated'],
            );
          }
          $customFieldsData = array_values($customFieldsData);
          $response = array(
            "status" => "success",
            "error" => false,
            "fields" => $customFieldsData
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
    function getPosts() {
      $data = json_decode(file_get_contents("php://input"));
      $search_by = !empty($data->search_keyword) ? htmlspecialchars($data->search_by, ENT_QUOTES, 'UTF-8') : '';
      $per_page = !empty($data->per_page) ? htmlspecialchars($data->per_page, ENT_QUOTES, 'UTF-8') : 5000;
      $offset = !empty($data->offset) ? htmlspecialchars($data->offset, ENT_QUOTES, 'UTF-8') : 0;
      $search_keyword = !empty($data->search_keyword) ? $data->search_keyword : NULL;
      $order_by = !empty($data->order_by) ? htmlspecialchars($data->order_by, ENT_QUOTES, 'UTF-8') : 'id';
      $order = !empty($data->order) ? htmlspecialchars($data->order, ENT_QUOTES, 'UTF-8') : 'DESC';

      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;

      $search_by_key = 'p.name';
      if ($search_by == 0) {
        $search_by_key = 'p.name';
      } else if ($search_by == 1) {
        $search_by_key = 'category_id';
      } else if ($search_by == 2) {
        $search_by_key = 'start';
      } else if ($search_by == 3) {
        $search_by_key = 'end';
      } else if ($search_by == 4) {
        $search_by_key = 'assigned_date';
      }

      $db = new Connect;
      $is_month = array_search($search_by, [4]);
      $is_single_date = array_search($search_by, [2,3]);

      if ($is_month > -1) {
        $posts_query = "SELECT
        p.id, p.post_type_id AS post_type_id, p.category_id, p.name, p.slug, p.desc, p.last_updated, p.thumbnail, p.start, p.end, p.fee, p.assigned_date AS assigned_date, c.name AS cat_name
        FROM posts AS p
        LEFT JOIN categories AS c
        ON p.category_id = c.id
        WHERE p.post_type_id = $post_type_id AND MONTH($search_by_key) = MONTH('$search_keyword') ORDER BY $order_by $order LIMIT $per_page OFFSET $offset";
        $posts_statement = $db->prepare($posts_query);
        $posts_statement->execute();
        $postData = array();
        while($OutputData=$posts_statement->fetch(PDO::FETCH_ASSOC)){
          $postData[$OutputData['id']]=array(
            'id' => $OutputData['id'],
            'post_type_id' => $OutputData['post_type_id'],
            'category_id' => $OutputData['category_id'],
            'cat_name' => $OutputData['cat_name'],
            'name' => $OutputData['name'],
            'slug' => $OutputData['slug'],
            'desc' => $OutputData['desc'],
            'last_updated' => $OutputData['last_updated'],
            'thumbnail' => $OutputData['thumbnail'],
            'start' => $OutputData['start'],
            'end' => $OutputData['end'],
            'fee' => $OutputData['fee'],
          );
        }

        $post_count_query = "SELECT COUNT(*) AS record_count FROM posts WHERE MONTH($search_by_key) = MONTH('$search_keyword')";
        $post_count_statement = $db->prepare($post_count_query);
        $post_count_statement->execute();
        $post_count_row = $post_count_statement->fetch();

        $postData = array_values($postData);
        $post_data = (object) [
          'status' => 'success',
          'error' => false,
          'record_count'=> $post_count_row['record_count'],
          'records' => $postData
        ];
        http_response_code(200);
        return json_encode($post_data);
      } else if ($is_single_date) {
        $posts_query = "SELECT
        p.id, p.post_type_id AS post_type_id, p.category_id, p.name, p.slug, p.desc, p.last_updated, p.thumbnail, p.start, p.end, p.fee, p.assigned_date AS assigned_date, c.name AS cat_name
        FROM posts AS p
        LEFT JOIN categories AS c
        ON p.category_id = c.id
        WHERE p.post_type_id = $post_type_id AND DATE($search_by_key) = DATE('$search_keyword') ORDER BY $order_by $order LIMIT $per_page OFFSET $offset";
        // if (!empty($category_id)) {
        //   $posts_query . " AND p.category_id = $category_id";
        // }
        $posts_statement = $db->prepare($posts_query);
        $posts_statement->execute();
        $postData = array();
        while($OutputData=$posts_statement->fetch(PDO::FETCH_ASSOC)){
          $postData[$OutputData['id']]=array(
            'id' => $OutputData['id'],
            'post_type_id' => $OutputData['post_type_id'],
            'category_id' => $OutputData['category_id'],
            'cat_name' => $OutputData['cat_name'],
            'name' => $OutputData['name'],
            'slug' => $OutputData['slug'],
            'desc' => $OutputData['desc'],
            'last_updated' => $OutputData['last_updated'],
            'thumbnail' => $OutputData['thumbnail'],
            'start' => $OutputData['start'],
            'end' => $OutputData['end'],
            'fee' => $OutputData['fee'],
          );
        }

        $post_count_query = "SELECT COUNT(*) AS record_count FROM posts WHERE DATE($search_by_key) = DATE('$search_keyword')";
        $post_count_statement = $db->prepare($post_count_query);
        $post_count_statement->execute();
        $post_count_row = $post_count_statement->fetch();

        $postData = array_values($postData);
        $post_data = (object) [
          'status' => 'success',
          'error' => false,
          'record_count'=> $post_count_row['record_count'],
          'records' => $postData
        ];
        http_response_code(200);
        return json_encode($post_data);
      } else {
        $posts_query = "SELECT
        p.id, p.post_type_id AS post_type_id, p.category_id, p.name, p.slug, p.desc, p.last_updated, p.thumbnail, p.start, p.end, p.fee, p.assigned_date AS assigned_date, c.name AS cat_name
        FROM posts AS p
        LEFT JOIN categories AS c
        ON p.category_id = c.id
        WHERE p.post_type_id = $post_type_id AND $search_by_key LIKE '%$search_keyword%' ORDER BY $order_by $order LIMIT $per_page OFFSET $offset";
        // if (!empty($category_id)) {
        //   $posts_query . " AND p.category_id = $category_id";
        // }
        $posts_statement = $db->prepare($posts_query);
        $posts_statement->execute();
        $postData = array();
        while($OutputData=$posts_statement->fetch(PDO::FETCH_ASSOC)){
          $postData[$OutputData['id']]=array(
            'id' => $OutputData['id'],
            'post_type_id' => $OutputData['post_type_id'],
            'category_id' => $OutputData['category_id'],
            'cat_name' => $OutputData['cat_name'],
            'name' => $OutputData['name'],
            'slug' => $OutputData['slug'],
            'desc' => $OutputData['desc'],
            'last_updated' => $OutputData['last_updated'],
            'thumbnail' => $OutputData['thumbnail'],
            'start' => $OutputData['start'],
            'end' => $OutputData['end'],
            'fee' => $OutputData['fee'],
          );
        }

        $post_count_query = "SELECT COUNT(*) AS record_count FROM posts AS p WHERE $search_by_key LIKE '%$search_keyword%'";
        $post_count_statement = $db->prepare($post_count_query);
        $post_count_statement->execute();
        $post_count_row = $post_count_statement->fetch();

        $postData = array_values($postData);
        $post_data = (object) [
          'status' => 'success',
          'error' => false,
          'record_count'=> $post_count_row['record_count'],
          'records' => $postData
        ];
        http_response_code(200);
        return json_encode($post_data);
      }
    }
    function getPost() {
      $data = json_decode(file_get_contents("php://input"));
      $lesson_id = !empty($data->lesson_id) ? htmlspecialchars($data->lesson_id, ENT_QUOTES, 'UTF-8') : '';

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT
        p.id AS id,
        p.post_type_id AS post_type_id,
        p.category_id AS category_id,
        p.`name` AS name,
        p.slug AS slug,
        p.`desc` AS `desc`,
        p.last_updated AS last_updated,
        p.thumbnail AS thumbnail,
        p.image AS image,
        p.`start` AS start,
        p.`end` AS end,
        p.user_id AS user_id,
        p.assignee_id AS assignee_id,
        p.fee AS fee,
        p.assigned_date AS assigned_date
        FROM posts AS p
        WHERE p.id = $lesson_id";
        $statement = $db->prepare($query);
        $statement->execute();
        $postData = array();
        while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
          $postData[$OutputData['id']]=array(
            'id' => $OutputData['id'],
            'post_type_id' => $OutputData['post_type_id'],
            'category_id' => $OutputData['category_id'],
            'name' => $OutputData['name'],
            'slug' => $OutputData['slug'],
            'desc' => $OutputData['desc'],
            'last_updated' => $OutputData['last_updated'],
            'thumbnail' => $OutputData['thumbnail'],
            'start' => $OutputData['start'],
            'end' => $OutputData['end'],
            'assigned_date' => $OutputData['assigned_date'],
            'assignee_id' => $OutputData['assignee_id'],
            'fee' => $OutputData['fee'],
          );
        }
        $postData = array_values($postData);
        $response = array(
          "status" => "success",
          "error" => false,
          "post" => $postData[0]
        );
        http_response_code(200);
        echo json_encode($response);
      }
    }
    function getVideos() {
      $data = json_decode(file_get_contents("php://input"));
      $lesson_id = !empty($data->lesson_id) ? htmlspecialchars($data->lesson_id, ENT_QUOTES, 'UTF-8') : '';

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT
        v.id AS id,
        v.title AS title,
        v.src AS src,
        v.duration AS duration,
        v.post_id AS post_id,
        v.display_order AS display_order,
        v.service AS service,
        v.last_updated AS last_updated,
        v.given_attempts AS given_attempts
        FROM videos AS v
        WHERE post_id = $lesson_id";
        $statement = $db->prepare($query);
        $statement->execute();
        $postData = array();
        while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
          $postData[$OutputData['id']]=array(
            'id' => $OutputData['id'],
            'title' => $OutputData['title'],
            'src' => $OutputData['src'],
            'duration' => $OutputData['duration'],
            'post_id' => $OutputData['post_id'],
            'display_order' => $OutputData['display_order'],
            'service' => $OutputData['service'],
            'last_updated' => $OutputData['last_updated'],
            'given_attempts' => $OutputData['given_attempts']
          );
        }
        $postData = array_values($postData);
        $response = array(
          "status" => "success",
          "error" => false,
          "videos" => $postData
        );
        http_response_code(200);
        echo json_encode($response);
      }
    }
    function updatePost() {
      $data = json_decode(file_get_contents("php://input"));
      $id = !empty($data->id) ? htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $category_id = !empty($data->category_id) ? htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8') : NULL;
      $name = !empty($data->name) ? htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') : '';
      $slug = !empty($data->slug) ? htmlspecialchars($data->slug, ENT_QUOTES, 'UTF-8') : '';
      $desc = !empty($data->desc) ? htmlspecialchars($data->desc, ENT_QUOTES, 'UTF-8') : '';
      $thumbnail = !empty($data->thumbnail) ? htmlspecialchars($data->thumbnail, ENT_QUOTES, 'UTF-8') : '';
      $image = !empty($data->image) ? htmlspecialchars($data->image, ENT_QUOTES, 'UTF-8') : '';
      $start = !empty($data->start) ? htmlspecialchars($data->start, ENT_QUOTES, 'UTF-8') : date("Y-m-d H:i:s");
      $end = !empty($data->end) ? htmlspecialchars($data->end, ENT_QUOTES, 'UTF-8') : '3000-12-12 00:00:00';
      $assigned_date = !empty($data->assigned_date) ? htmlspecialchars($data->assigned_date, ENT_QUOTES, 'UTF-8') : NULL;
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $assignee_id = !empty($data->assignee_id) ? htmlspecialchars($data->assignee_id, ENT_QUOTES, 'UTF-8') : '';
      $attempts = !empty($data->attempts) ? htmlspecialchars($data->attempts, ENT_QUOTES, 'UTF-8') : NULL;
      $fee = !empty($data->fee) ? htmlspecialchars($data->fee, ENT_QUOTES, 'UTF-8') : '';
      $videos = !empty($data->videos) ? $data->videos : [];

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "UPDATE posts SET
        post_type_id = $post_type_id,
        category_id = $category_id,
        name = '$name',
        slug = '$slug',
        `desc` = '$desc',
        thumbnail = '$thumbnail',
        image = '$image',
        start = '$start',
        end = '$end',
        user_id =  $user_id,
        assignee_id = $assignee_id,
        fee = $fee,
        assigned_date = '$assigned_date'
        WHERE id = $id";
        $statement = $db->prepare($query);
        try {
          if ($statement->execute()) {
            $last_id = $db->lastInsertId();
            $this->updateVideos($db, $id, $videos, $attempts);
            $response = array(
              "status" => "success",
              "error" => false,
              "message" => "Post is successfully updated"
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
    function getClassGroups() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $category_id = !empty($data->category_id) ? htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8') : NULL;

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT * FROM categories WHERE parent_category_id = :category_id AND post_type_id = :post_type_id";
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
              "cats" => $categoriesData
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
    function getPaymentSummary() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $item_id = !empty($data->item_id) ? htmlspecialchars($data->item_id, ENT_QUOTES, 'UTF-8') : NULL;
      $assignee_id = !empty($data->assignee_id) ? htmlspecialchars($data->assignee_id, ENT_QUOTES, 'UTF-8') : NULL;

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT
        ac.id AS id, ac.post_type_id AS post_type_id, ac.category_id AS category_id, ac.user_id AS assignee_id, ac.fee AS fee,
        ca.`name` AS cat_name,
        u.firstname AS firstname, u.lastname AS lastname,
        me.user_gender AS gender
        FROM assigned_categories AS ac
        LEFT JOIN categories AS ca
        ON ca.id = ac.category_id
        LEFT JOIN useres AS u
        ON u.id = ac.user_id
        LEFT JOIN users_meta AS me
        ON u.id = me.user_id
        WHERE ac.post_type_id = :post_type_id AND ac.category_id = :item_id AND ac.user_id = :assignee_id";
        $statement = $db->prepare($query);
        try {
          if (
            $statement->execute([
              'item_id' => $item_id,
              'post_type_id' => $post_type_id,
              'assignee_id' => $assignee_id
            ])
          ) {
            $summaryData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $summaryData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'post_type_id'=> $OutputData['post_type_id'],
                'category_id'=> $OutputData['category_id'],
                'assignee_id'=> $OutputData['assignee_id'],
                'fee'=> $OutputData['fee'],
                'cat_name'=> $OutputData['cat_name'],
                'firstname'=> $OutputData['firstname'],
                'lastname'=> $OutputData['lastname'],
                'honorific'=> $OutputData['gender'] == 1 ? 'Mr' : 'Ms'
              );
            }
            $summaryData = array_values($summaryData);
            $response = array(
              "status" => "success",
              "error" => false,
              "summary" => $summaryData[0]
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
    function addEnrollment() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $assignee_id = !empty($data->assignee_id) ? htmlspecialchars($data->assignee_id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $assignee_id = !empty($data->assignee_id) ? htmlspecialchars($data->assignee_id, ENT_QUOTES, 'UTF-8') : NULL;
      $item_id = !empty($data->item_id) ? htmlspecialchars($data->item_id, ENT_QUOTES, 'UTF-8') : NULL;
      $cat_id = !empty($data->cat_id) ? htmlspecialchars($data->cat_id, ENT_QUOTES, 'UTF-8') : NULL;
      $enrollment_mode = !empty($data->enrollment_mode) ? htmlspecialchars($data->enrollment_mode, ENT_QUOTES, 'UTF-8') : NULL;
      $slip_id = !empty($data->slip_id) ? htmlspecialchars($data->slip_id, ENT_QUOTES, 'UTF-8') : NULL;
      $date = !empty($data->date) ? htmlspecialchars($data->date, ENT_QUOTES, 'UTF-8') : NULL;
      $slip_ref = !empty($data->slip_ref) ? htmlspecialchars($data->slip_ref, ENT_QUOTES, 'UTF-8') : NULL;
      $student_email = !empty($data->student_email) ? htmlspecialchars($data->student_email, ENT_QUOTES, 'UTF-8') : NULL;
      $student_firstname = !empty($data->student_firstname) ? htmlspecialchars($data->student_firstname, ENT_QUOTES, 'UTF-8') : NULL;
      $cat_name = !empty($data->cat_name) ? htmlspecialchars($data->cat_name, ENT_QUOTES, 'UTF-8') : NULL;
      $subject = "PENDING Your enrrollment [AKURATA.LK]";

      $bytes = random_bytes(5);
      $invoice = bin2hex($bytes);
      $payment_type = 1;

      $Validations = new Validations;
      $Email = new Email;

      if ($Validations->tokenValidate()) {
        $db = new Connect;

        $category_details_query = "SELECT name, slug FROM categories WHERE id = $cat_id";
        $category_details_statement = $db->prepare($category_details_query);
        $category_details_statement->execute();
        $category_details_row = $category_details_statement->fetch();
        $category_name = $category_details_row["name"];
        $category_slug = $category_details_row["slug"];

        $conductor_details_query = "SELECT firstname, lastname FROM useres WHERE id = $assignee_id";
        $conductor_details_statement = $db->prepare($conductor_details_query);
        $conductor_details_statement->execute();
        $conductor_details_row = $conductor_details_statement->fetch();
        $conductor_firstname = $conductor_details_row["firstname"];
        $conductor_lastname = $conductor_details_row["lastname"];

        $student_details_query = "SELECT firstname, lastname, email FROM useres WHERE id = $user_id";
        $student_details_statement = $db->prepare($student_details_query);
        $student_details_statement->execute();
        $student_details_row = $student_details_statement->fetch();
        $student_details_firstname = $student_details_row["firstname"];
        $student_details_lastname = $student_details_row["lastname"];
        $student_details_email = $student_details_row["email"];

        $query_fee = "SELECT fee FROM assigned_categories WHERE post_type_id = $post_type_id AND category_id = $cat_id AND user_id = $assignee_id";
        $statement_fee = $db->prepare($query_fee);
        $statement_fee->execute();
        $row_fee = $statement_fee->fetch();
        $row_fee = $row_fee["fee"];

        $enrolled_date = $date;

        $query = "INSERT INTO enrollments (enrollment_mode, post_type_id, user_id, item_id, assignee_id, fee, enrolled_date, reference, slip_ref, cat_id)
        VALUES ($enrollment_mode, $post_type_id, $user_id, $item_id, $assignee_id, $row_fee, '$enrolled_date', '$invoice', '$slip_ref', $cat_id)";
        $statement = $db->prepare($query);

        try {
          if ($statement->execute()) {

            $last_id = $db->lastInsertId();

            $payer = $student_details_firstname . ' ' . $student_details_lastname;
            $conductor = $conductor_firstname . ' ' . $conductor_lastname;

            $transaction_state = $payment_type == 1 ? 4 : 1;

            $transaction_query = "INSERT INTO transctions (`from`, `to`, enrollment_id, transaction_time, amount, payment_types, slip_id, item, item_slug, conductor, payer_name, payer_email, transaction_state)
            VALUES ($user_id, $assignee_id, $last_id, '$enrolled_date', $row_fee, $payment_type, $slip_id, '$category_name', '$category_slug', '$conductor', '$payer', '$student_details_email', $transaction_state)";
            $transaction_statement = $db->prepare($transaction_query);

            try {
              if ($transaction_statement->execute()) {
                $email_obj = array(
                  "to" => $student_email,
                  "from" => "no_reply@akurata.lk",
                  "name" => $student_firstname,
                  "subject" => $subject,
                  "cat_name" => $cat_name,
                  "approval" => 0,
                  "invoice" => $invoice
                );
                if ($Email->sendApprovalEmail($email_obj)) {
                  $response = array(
                    "status" => "success",
                    "error" => false,
                    "message" => "Payment is in review"
                  );
                  http_response_code(200);
                  echo json_encode($response);
                } else {
                  $response = array(
                    "status" => "success",
                    "error" => false,
                    "message" => 'Payment is in review but failed to send email'
                  );
                  http_response_code(200);
                  echo json_encode($response);
                }
              }
            } catch (Exception $e) {
              http_response_code(400);
              $db->rollback();
              throw $e;
            }
          }
        } catch (Exception $e) {
          http_response_code(400);
          $db->rollback();
          throw $e;
        }
      }
    }
    function getPayments() {
      $data = json_decode(file_get_contents("php://input"));
      $search_by = !empty($data->search_keyword) ? htmlspecialchars($data->search_by, ENT_QUOTES, 'UTF-8') : 'u.firstname';
      $per_page = !empty($data->per_page) ? htmlspecialchars($data->per_page, ENT_QUOTES, 'UTF-8') : 5000;
      $offset = !empty($data->offset) ? htmlspecialchars($data->offset, ENT_QUOTES, 'UTF-8') : 0;
      $search_keyword = !empty($data->search_keyword) ? $data->search_keyword : NULL;
      $order_by = !empty($data->order_by) ? htmlspecialchars($data->order_by, ENT_QUOTES, 'UTF-8') : 'id';
      $order = !empty($data->order) ? htmlspecialchars($data->order, ENT_QUOTES, 'UTF-8') : 'DESC';

      $enrollment_month = !empty($data->enrollment_month) ? htmlspecialchars($data->enrollment_month, ENT_QUOTES, 'UTF-8') : NULL;
      $state = !empty($data->state) ? htmlspecialchars($data->state, ENT_QUOTES, 'UTF-8') : NULL;
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $item_id = !empty($data->item_id) ? htmlspecialchars($data->item_id, ENT_QUOTES, 'UTF-8') : NULL;
      $assignee_id = !empty($data->assignee_id) ? htmlspecialchars($data->assignee_id, ENT_QUOTES, 'UTF-8') : NULL;

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT
        e.id AS id, e.enrollment_mode AS enrollment_mode, e.post_type_id AS post_type_id, e.user_id AS `user_id`, e.item_id AS item_id, e.fee AS fee, e.last_updated AS last_updated, e.approval AS approval, e.enrolled_date AS enrolled_date, e.assignee_id AS assignee_id, e.reference AS invoice,
        m.`name` AS `mode`,
        u.firstname AS student_firstname, u.lastname AS student_lastname, u.email AS student_email, u.username AS student_username,
        cat.`name` AS cat_name, cat.slug AS cat_slug,
        app.state AS `state`,
        tr.payment_types AS payment_type_id,
        pay.`name` AS payment_type,
        slip.file_name AS `file_name`,
        paid_from.firstname AS paid_from_firstname, paid_from.lastname AS paid_from_lastname, paid_from.username AS paid_from_username,
        paid_to.firstname AS paid_to_firstname, paid_to.lastname AS paid_to_lastname, paid_to.username AS paid_to_username
        FROM enrollments AS e
        LEFT JOIN enrollment_modes AS m
        ON e.enrollment_mode = m.id
        LEFT JOIN useres AS u
        ON e.user_id = u.id
        LEFT JOIN assigned_categories AS a_cat
        ON e.item_id = a_cat.id
        LEFT JOIN categories AS cat
        ON a_cat.category_id = cat.id
        LEFT JOIN approvals AS app
        ON e.approval = app.id
        LEFT JOIN transctions AS tr
        ON e.id = tr.enrollment_id
        LEFT JOIN payment_types AS pay
        ON tr.payment_types = pay.id
        LEFT JOIN bank_slips AS slip
        ON tr.slip_id = slip.id
        LEFT JOIN useres AS paid_from
        ON tr.`from` = paid_from.id
        LEFT JOIN useres AS paid_to
        ON tr.`to` = paid_to.id
        WHERE e.post_type_id = 1 AND MONTH(e.enrolled_date) = MONTH('$enrollment_month') AND $search_by LIKE '%$search_keyword%' AND e.approval = '$state'";

        if (!empty($item_id)) {
          $query = $query . " AND e.cat_id = $item_id";          
        }

        if (!empty($assignee_id)) {
          $query = $query . " AND e.assignee_id = $assignee_id";          
        }

        $query = $query . " ORDER BY $order_by $order LIMIT $per_page OFFSET $offset";

        $statement = $db->prepare($query);
        try {
          if (
            $statement->execute()
          ) {
            $summaryData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $summaryData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'enrollment_mode'=> $OutputData['enrollment_mode'],
                'post_type_id'=> $OutputData['post_type_id'],
                'user_id'=> $OutputData['user_id'],
                'item_id'=> $OutputData['item_id'],
                'fee'=> $OutputData['fee'],
                'last_updated'=> $OutputData['last_updated'],
                'approval'=> $OutputData['approval'],
                'enrolled_date'=> $OutputData['enrolled_date'],
                'mode'=> $OutputData['mode'],
                'student_firstname'=> $OutputData['student_firstname'],
                'student_lastname'=> $OutputData['student_lastname'],
                'student_email'=> $OutputData['student_email'],
                'student_username'=> $OutputData['student_username'],
                'cat_name'=> $OutputData['cat_name'],
                'cat_slug'=> $OutputData['cat_slug'],
                'state'=> $OutputData['state'],
                'payment_type_id'=> $OutputData['payment_type_id'],
                'payment_type'=> $OutputData['payment_type'],
                'file_name'=> $OutputData['file_name'],
                'paid_from_firstname'=> $OutputData['paid_from_firstname'],
                'paid_from_lastname'=> $OutputData['paid_from_lastname'],
                'paid_from_username'=> $OutputData['paid_from_username'],
                'paid_to_firstname'=> $OutputData['paid_to_firstname'],
                'paid_to_lastname'=> $OutputData['paid_to_lastname'],
                'paid_to_username'=> $OutputData['paid_to_username']
              );
            }
            $summaryData = array_values($summaryData);
        
            $count_query = "SELECT COUNT(*) AS record_count
            FROM enrollments AS e
            LEFT JOIN useres AS u
            ON e.user_id = u.id
            WHERE e.post_type_id = 1 AND MONTH(e.enrolled_date) = MONTH('$enrollment_month') AND $search_by LIKE '%$search_keyword%' AND e.approval = '$state'";
            $count_statement = $db->prepare($count_query);
            $count_statement->execute();
            $count_row = $count_statement->fetch();

            $response = array(
              "status" => "success",
              "error" => false,
              "records" => $summaryData,
              'record_count'=> $count_row['record_count']
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
    function updatePayments() {
      $data = json_decode(file_get_contents("php://input"));
      $payment_id = !empty($data->id) ? htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8') : NULL;
      $approval = !empty($data->status) ? htmlspecialchars($data->status, ENT_QUOTES, 'UTF-8') : NULL;
      $student_email = !empty($data->student_email) ? htmlspecialchars($data->student_email, ENT_QUOTES, 'UTF-8') : NULL;
      $student_firstname = !empty($data->student_firstname) ? htmlspecialchars($data->student_firstname, ENT_QUOTES, 'UTF-8') : NULL;
      $cat_name = !empty($data->cat_name) ? htmlspecialchars($data->cat_name, ENT_QUOTES, 'UTF-8') : NULL;
      $reason = !empty($data->reason) ? htmlspecialchars($data->reason, ENT_QUOTES, 'UTF-8') : NULL;
      $invoice = !empty($data->invoice) ? htmlspecialchars($data->invoice, ENT_QUOTES, 'UTF-8') : NULL;

      $Validations = new Validations;
      $Email = new Email;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "UPDATE enrollments SET
        approval = :approval
        WHERE id = :payment_id";
        $statement = $db->prepare($query);

        $trans_query = "UPDATE transctions SET
        transaction_state = :approval
        WHERE enrollment_id = :payment_id";
        $trans_statement = $db->prepare($trans_query);
        try {
          if ($statement->execute([
            'approval' => $approval,
            'payment_id' => $payment_id
          ]) && $trans_statement->execute([
            'approval' => $approval,
            'payment_id' => $payment_id
            ])) {
            $msg = '';
            $subject = '';
            if ($approval == 1) {
              $msg = "Payment is successfully approved";
              $subject = "APPROVED Your enrrollment [AKURATA.LK]";
            } else if ($approval == 2) {
              $msg = "Payment is successfully rejected";
              $subject = "REJECTED Your enrrollment [AKURATA.LK]";
            }
            $email_obj = array(
              "to" => $student_email,
              "from" => "no_reply@akurata.lk",
              "name" => $student_firstname,
              "subject" => $subject,
              "cat_name" => $cat_name,
              "approval" => $approval,
              "reason" => $reason,
              "invoice" => $invoice
            );
            if ($Email->sendApprovalEmail($email_obj)) {
              $response = array(
                "status" => "success",
                "error" => false,
                "message" => $msg
              );
              http_response_code(200);
              echo json_encode($response);
            } else {
              $response = array(
                "status" => "error",
                "error" => true,
                "message" => $msg . ' but failed to send email'
              );
              http_response_code(400);
              echo json_encode($response);
            }
          }
        } catch (Exception $e) {
          http_response_code(400);
          $db->rollback();
          throw $e;
        }
      }
    }
    function getEnrolledClasses() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT id, item_id, assignee_id FROM enrollments
        WHERE post_type_id = 1 AND user_id = '$user_id' AND approval = 1";

        $statement = $db->prepare($query);
        try {
          if (
            $statement->execute()
          ) {
            $summaryData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $summaryData[$OutputData['id']]=array(
                'id'=> $OutputData['id'],
                'item_id'=> $OutputData['item_id'],
                'assignee_id'=> $OutputData['assignee_id']
              );
            }
            $summaryData = array_values($summaryData);
            $response = array(
              "status" => "success",
              "error" => false,
              "classes" => $summaryData
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
    function getEnrolledClassesFull() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $enrollment_month = !empty($data->enrollment_month) ? htmlspecialchars($data->enrollment_month, ENT_QUOTES, 'UTF-8') : NULL;
      $state = !empty($data->state) ? htmlspecialchars($data->state, ENT_QUOTES, 'UTF-8') : NULL;
      
      $per_page = !empty($data->per_page) ? htmlspecialchars($data->per_page, ENT_QUOTES, 'UTF-8') : 5000;
      $offset = !empty($data->offset) ? htmlspecialchars($data->offset, ENT_QUOTES, 'UTF-8') : 0;

      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT
        e.id AS id,
        e.enrollment_mode AS enrollment_mode_id,
        m.`name` AS enrollment_mode,
        e.post_type_id AS post_type_id,
        e.user_id AS user_id,
        u.username AS username,
        e.item_id AS item_id,
        c.`name` AS cat_name,
        c.slug AS cat_slug,
        e.assignee_id AS assignee_id,
        a.username AS assignee_username,
        a.firstname AS assignee_firstname,
        a.lastname AS assignee_lastname,
        e.fee AS fee,
        e.last_updated AS last_updated,
        e.approval AS approval,
        ap.state AS approval_state,
        e.enrolled_date AS enrolled_date,
        p.id AS post_id,
        p.`name` AS post_name,
        p.slug AS post_slug
        FROM enrollments AS e
        LEFT JOIN enrollment_modes AS m
        ON e.enrollment_mode = m.id
        LEFT JOIN useres AS u
        ON e.user_id = u.id
        LEFT JOIN assigned_categories AS acat
        ON e.item_id = acat.id
        LEFT JOIN categories AS c
        ON acat.category_id = c.id
        LEFT JOIN useres AS a
        ON e.assignee_id = a.id
        LEFT JOIN approvals AS ap
        ON e.approval = ap.id
        LEFT JOIN posts AS p
        ON p.category_id = c.id
        WHERE e.post_type_id = 1 AND e.user_id = '$user_id' AND e.approval = '$state' AND MONTH(e.enrolled_date) = MONTH('$enrollment_month') LIMIT $per_page OFFSET $offset";

        $statement = $db->prepare($query);
        try {
          if (
            $statement->execute()
          ) {
            $summaryData = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $summaryData[$OutputData['post_id']]=array(
                'id'=> $OutputData['id'],
                'post_id'=> $OutputData['post_id'],
                'item_id'=> $OutputData['item_id'],
                'cat_name'=> $OutputData['cat_name'],
                'cat_slug'=> $OutputData['cat_slug'],
                'assignee_id'=> $OutputData['assignee_id'],
                'assignee_username'=> $OutputData['assignee_username'],
                'assignee_firstname'=> $OutputData['assignee_firstname'],
                'assignee_lastname'=> $OutputData['assignee_lastname'],
                'fee'=> $OutputData['fee'],
                'approval_state'=> $OutputData['approval_state'],
                'enrolled_date'=> $OutputData['enrolled_date'],
                'post_id'=> $OutputData['post_id'],
                'post_name'=> $OutputData['post_name'],
                'post_slug'=> $OutputData['post_slug']
              );
            }
            $summaryData = array_values($summaryData);
            
            $count_query = "SELECT COUNT(*) AS record_count
            FROM enrollments AS e
            LEFT JOIN useres AS u
            ON e.user_id = u.id
            WHERE e.post_type_id = 1 AND e.user_id = '$user_id' AND e.approval = '$state' AND MONTH(e.enrolled_date) = MONTH('$enrollment_month')";
            $count_statement = $db->prepare($count_query);
            $count_statement->execute();
            $count_row = $count_statement->fetch();

            $response = array(
              "status" => "success",
              "error" => false,
              "classes" => $summaryData,
              'record_count'=> $count_row['record_count']
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
    function getLesson() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $item_id = !empty($data->itemId) ? htmlspecialchars($data->itemId, ENT_QUOTES, 'UTF-8') : '';
      $post_id = !empty($data->postId) ? htmlspecialchars($data->postId, ENT_QUOTES, 'UTF-8') : '';
      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $query = "SELECT
        e.id AS enrollment_id,
        e.user_id AS user_id,
        e.item_id AS item_id,
        e.assignee_id AS assignee_id,
        e.fee AS fee,
        e.approval AS approval,
        a.id AS assigned_cat_id,
        a.category_id AS category_id,
        p.id AS post_id,
        p.`name` AS post_name,
        p.slug AS post_slug,
        p.thumbnail AS post_thumbnail
        FROM enrollments AS e
        LEFT JOIN assigned_categories AS a
        ON e.item_id = a.id
        LEFT JOIN categories AS c
        ON a.category_id = c.id
        LEFT JOIN posts AS p
        ON a.category_id = p.category_id
        WHERE e.user_id = $user_id AND e.item_id = $item_id AND e.approval = 1 AND p.id = $post_id";

        $videos_query = "SELECT
        id,
        title,
        src,
        duration,
        display_order,
        service,
        given_attempts
        FROM videos
        WHERE post_id = $post_id
        ORDER BY display_order ASC";
        $statement = $db->prepare($query);
        $videos_statement = $db->prepare($videos_query);
        $statement->execute();
        $videos_statement->execute();
        $postData = array();
        $videoData = array();
        while($VideoOutputData=$videos_statement->fetch(PDO::FETCH_ASSOC)){
          $videoData[$VideoOutputData['id']]=array(
            'id' => $VideoOutputData['id'],
            'title' => $VideoOutputData['title'],
            'src' => $VideoOutputData['src'],
            'duration' => $VideoOutputData['duration'],
            'display_order' => $VideoOutputData['display_order'],
            'service' => $VideoOutputData['service'],
            'attempts' => $VideoOutputData['given_attempts']
          );
        }
        while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
          $postData[$OutputData['post_id']]=array(
            'id' => $OutputData['enrollment_id'],
            'post_id' => $OutputData['post_id'],
            'user_id' => $OutputData['user_id'],
            'item_id' => $OutputData['item_id'],
            'assignee_id' => $OutputData['assignee_id'],
            'fee' => $OutputData['fee'],
            'approval' => $OutputData['approval'],
            'assigned_cat_id' => $OutputData['assigned_cat_id'],
            'category_id' => $OutputData['category_id'],
            'post_id' => $OutputData['post_id'],
            'post_name' => $OutputData['post_name'],
            'post_slug' => $OutputData['post_slug'],
            'post_thumbnail' => $OutputData['post_thumbnail']
          );
        }
        $postData = array_values($postData);
        $videoData = array_values($videoData);
        $response = array(
          "status" => "success",
          "error" => false,
          "lesson" => $postData[0],
          "videos" => $videoData
        );
        http_response_code(200);
        echo json_encode($response);
      }
    }
    function initAttempts() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_id = !empty($data->post_id) ? htmlspecialchars($data->post_id, ENT_QUOTES, 'UTF-8') : NULL;
      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;

        $get_videos_query = "SELECT id, given_attempts FROM videos WHERE post_id = $post_id";
        $get_videos_statement = $db->prepare($get_videos_query);
        $get_videos_statement->execute();
        $videos = [];
        while($video=$get_videos_statement->fetch(PDO::FETCH_ASSOC)){
          $videos[]=array(
            'id'=> $video['id'],
            'given_attempts'=> $video['given_attempts']
          );
        }
        $length = count($videos);
        $count = 1;
        foreach ($videos as $video) {
          $video_id = $video['id'];
          $video_attempts = $video['given_attempts'];
          $check_query = "SELECT COUNT(*) AS atmpts FROM video_attempts WHERE post_id = $post_id AND video_id = $video_id AND student_id = $user_id";
          $check_statement = $db->prepare($check_query);
          $check_statement->execute();
          $check_row = $check_statement->fetch();
          $atmpts = json_encode($check_row["atmpts"]);
          if ($atmpts == 0) {
            $insert_query = "INSERT INTO video_attempts (post_id, video_id, student_id, attempts) VALUES ($post_id, $video_id, $user_id, $video_attempts)";
            $insert_statement = $db->prepare($insert_query);
            $insert_statement->execute();
          }
          if ($length == $count) {
            $check_query = "SELECT id, video_id, attempts FROM video_attempts WHERE post_id = $post_id AND student_id = $user_id";
            $check_statement = $db->prepare($check_query);
            $check_statement->execute();
            $attempts = array();
            while($video=$check_statement->fetch(PDO::FETCH_ASSOC)){
              $attempts[]=array(
                'id'=> $video['id'],
                'video_id'=> $video['video_id'],
                'attempts'=> $video['attempts']
              );
            }
            $response = array(
              "status" => "success",
              "error" => false,
              "attempts" => $attempts
            );
            http_response_code(200);
            echo json_encode($response);
          } else {
            ++$count;
          }
        }
      }
    }
    function setAttempts() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $post_id = !empty($data->post_id) ? htmlspecialchars($data->post_id, ENT_QUOTES, 'UTF-8') : NULL;
      $video_id = !empty($data->video_id) ? htmlspecialchars($data->video_id, ENT_QUOTES, 'UTF-8') : NULL;
      $current_attempt = !empty($data->current_attempt) ? htmlspecialchars($data->current_attempt, ENT_QUOTES, 'UTF-8') : NULL;
      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;
        $current_attempt = $current_attempt - 1;
        $check_query = "UPDATE video_attempts SET attempts = :current_attempt WHERE post_id = :post_id AND student_id = :user_id AND video_id = :video_id";
        $check_statement = $db->prepare($check_query);
        try {
          if (
            $check_statement->execute([
              'current_attempt' => $current_attempt,
              'post_id' => $post_id,
              'user_id' => $user_id,
              'video_id' => $video_id
            ])
          ) {
            $response = array(
              "status" => "success",
              "error" => false,
              "message" => "Attempt updated"
            );
            http_response_code(200);
            echo json_encode($response);
          }
        } catch (Exception $e) {
          $db->rollback();
          throw $e;
        }
      }
    }
    function deleteCategoryPermanently() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = !empty($data->user_id) ? htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8') : NULL;
      $category_id = !empty($data->category_id) ? htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8') : NULL;
      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        $db = new Connect;

        $has_count_query = "SELECT COUNT(*) AS has_child FROM categories WHERE id = $category_id AND has_child = 1";
        $has_count_statement = $db->prepare($has_count_query);
        $has_count_statement->execute();
        $has_count_row = $has_count_statement->fetch();
        $has_parent = $has_count_row['has_child'];

        if ($has_parent > 0) {
          http_response_code(400);
          echo "Please delete child categories first";
        } else {
          $query = "DELETE FROM categories WHERE id = $category_id";
          $statement = $db->prepare($query);
          try {
            if ($statement->execute([
                'category_id' => $category_id
              ])
            ) {
              $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Category successully deleted"
              );
              http_response_code(200);
              echo json_encode($response);
            } else {
              $has_count_query = "SELECT * FROM posts WHERE category_id = $category_id";
              $has_count_statement = $db->prepare($has_count_query);
              $has_count_statement->execute();
              $postsData = [];
              
              $assignedCategoriesData = [];
              $assigned_categories_query = "SELECT
              a.id AS id,
              a.user_id AS u_id,
              u.id AS user_id,
              u.firstname AS firstname,
              u.lastname AS lastname,
              c.`name` AS name
              FROM assigned_categories AS a
              LEFT JOIN useres AS u
              ON a.user_id = u.id
              LEFT JOIN categories AS c
              ON c.id = a.category_id
              WHERE category_id = $category_id";
              $assigned_categories_statement = $db->prepare($assigned_categories_query);
              $assigned_categories_statement->execute();

              while($OutputData=$assigned_categories_statement->fetch(PDO::FETCH_ASSOC)){
                $assignedCategoriesData[]=array(
                  'firstname' => $OutputData['firstname'],
                  'lastname' => $OutputData['lastname'],
                  'name' => $OutputData['name']
                );
              }
              if (count($postsData) > 0) {
                while($OutputData=$has_count_statement->fetch(PDO::FETCH_ASSOC)){
                  array_push($postsData, $OutputData['slug']);
                }
                http_response_code(400);
                $postsData = implode(' / ', $postsData);
                echo "First delete these posts assigned to this - (" . $postsData . ")";
              } else if (count($assignedCategoriesData) > 0) {
                http_response_code(400);
                echo "First delete " . $assignedCategoriesData[0]['name'] . " assigned under " . $assignedCategoriesData[0]['firstname'] . " " . $assignedCategoriesData[0]['lastname'];
              } else {
                http_response_code(400);
                echo "Something went wrong when deleting category";
              }
            }
          } catch (Exception $e) {
            http_response_code(400);
            $db->rollback();
            throw $e;
          }
        }
      }
    }
  }
  return $PostType = new PostType;
?>