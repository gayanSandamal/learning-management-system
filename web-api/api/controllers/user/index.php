<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class User {
    function AccountDetails() {
      $data = json_decode(file_get_contents("php://input"));
      $username = !empty($data->username) ? htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8') : NULL;
      $Validations = new Validations;
      $Encrypt = new Encrypt;
      if ($Validations->tokenValidate()) {
        if (empty($username)) {
          http_response_code(400);
          echo json_encode(array("message" => "required field is missing"));
        } else if (!$Validations->isUsername($username)->valid) {
          http_response_code(400);
          echo json_encode(array("message" => $Validations->isUsername($username)->message));
        } else {
          $db = new Connect;
          $statement = $db->prepare("SELECT
          u.id,
          u.username,
          u.firstname,
          u.lastname,
          u.email,
          u.is_email_verified,
          u.token,
          r.roleId AS roleId,
          r.label AS roleName
          FROM useres AS u
          LEFT JOIN roles AS r
          ON u.roleId = r.roleId
          WHERE u.username = :username");
          try {
            if (
              $statement->execute([
                'username' => $username
              ])
            ) {
              $Data = array();
              while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
                $Data[]=array(
                'id'=> $OutputData['id'],
                'username'=> $OutputData['username'],
                'firstname'=> $OutputData['firstname'],
                'lastname'=> $OutputData['lastname'],
                'email'=> $OutputData['email'],
                'is_email_verified'=> $OutputData['is_email_verified'],
                'roleTypeId' => $OutputData['roleId'],
                'roleName' => $OutputData['roleName']
                );
              }
              $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Successfully retreived data",
                "data" => $Data,
                "console_access" => ""
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
    function Verify() {
      $data = json_decode(file_get_contents("php://input"));
      $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
      $password = htmlspecialchars($data->token, ENT_QUOTES, 'UTF-8');
      $Validations = new Validations;
      $Encrypt = new Encrypt;

      if (empty($email)) {
        http_response_code(400);
        echo json_encode(array("message" => "required field is missing"));
      } else if (!$Validations->isEmail($email)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($email)->message));
      } else if (empty($password)) {
        http_response_code(400);
        echo json_encode(array("message" => "token is missing"));
      } else {
        $db = new Connect;
        $get_pw_statement = $db->prepare("SELECT `password` FROM useres WHERE email = :email");
        try {
          if (
            $get_pw_statement->execute(
              [
                'email' => $email
              ]
            )
          ) {
            $get_pw_row = $get_pw_statement->fetch();
            if ($get_pw_statement->rowCount() > 0) {
              $hash = $get_pw_row["password"];
              $is_verified = $password == $hash;
              if ($is_verified > 0) {
                $query = "UPDATE useres SET is_email_verified = 1 WHERE email = :email";
                $statement = $db->prepare($query);
                try {
                  if (
                    $statement->execute([
                      'email' => $email
                    ])
                  ) {
                    $response = array(
                      "status" => "success",
                      "error" => false,
                      "message" => "Successfully verified"
                    );
                    http_response_code(200);
                    echo json_encode($response);
                  }
                } catch (Exception $e) {
                  http_response_code(400);
                  $db->rollback();
                  throw $e;
                }
              } else {
                $response = array(
                  "status" => "error",
                  "error" => true,
                  "message" => "Username or password is incorrect"
                );
                http_response_code(400);
                echo json_encode($response);
              }
            } else {
              $response = array(
                "status" => "error",
                "error" => true,
                "message" => "Account doesn't exist"
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
    function ResetPassword() {
      $data = json_decode(file_get_contents("php://input"));
      $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
      $password = !empty($data->password) ? htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8') : null;
      $token = !empty($data->token) ? htmlspecialchars($data->token, ENT_QUOTES, 'UTF-8') : null;
      $is_verification_step = !empty($data->is_verification_step) ? htmlspecialchars($data->is_verification_step, ENT_QUOTES, 'UTF-8') : false;
      $Validations = new Validations;
      $Encrypt = new Encrypt;
      $Email = new Email;

      if (empty($email)) {
        http_response_code(400);
        echo json_encode(array("message" => "required field is missing"));
      } else if (!$Validations->isEmail($email)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isEmail($email)->message));
      } else if ($is_verification_step && !$Validations->strongPassword($password)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->strongPassword($password)->message));
      } else {
        $db = new Connect;
        if ($is_verification_step) {
          $is_user_query = "SELECT COUNT(*) AS allowed FROM useres WHERE email = :email AND token = :token";
          $is_user_statement = $db->prepare($is_user_query);
          try {
            if (
              $is_user_statement->execute(
                [
                  'token' => $token,
                  'email' => $email
                ]
              )
            ) {
              $row = $is_user_statement->fetch();
              $allowed = $row["allowed"];
              if ($allowed > 0) {
                $today = date('Y-m-d');
                $is_exp_query = "SELECT COUNT(*) AS allowed FROM useres WHERE email = '$email' AND email_reset_expiration >= '$today'";
                $is_exp_statement = $db->prepare($is_exp_query);
                $is_exp_statement->execute();
                $is_exp_row = $is_exp_statement->fetch();
                $is_valid = $is_exp_row["allowed"];
        
                if ($is_valid == 1) {
                  $statement = $db->prepare("UPDATE useres SET password = :password, email_reset_expiration = '0000-00-00' WHERE email = :email AND token = :token");
                  $encrypted_password = $Encrypt->password($password);
                  try {
                    if (
                      $statement->execute(
                        [
                          'token' => $token,
                          'password' => $encrypted_password,
                          'email' => $email
                        ]
                      )
                    ) {
                      $response = array(
                        "status" => "success",
                        "error" => false,
                        "message" => "Password successfully reset. Please login"
                      );
                      http_response_code(200);
                      echo json_encode($response);
                    } else {
                      $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Something went wrong when resetting your password"
                      );
                      http_response_code(400);
                      echo json_encode($response);
                    }
                  } catch (Exception $e) {
                    http_response_code(400);
                    $db->rollback();
                    throw $e;
                  }
                } else {
                  $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Email verification is expired or already reset. Please resend the email"
                  );
                  http_response_code(401);
                  echo json_encode($response);
                }
              } else {
                $response = array(
                  "status" => "error",
                  "error" => true,
                  "message" => "You are trying to reset the password of someone else account"
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
        } else {
          $get_record_statement = $db->prepare("SELECT firstname, email, token FROM useres WHERE email = :email");
          try {
            if (
              $get_record_statement->execute(
                [
                  'email' => $email
                ]
              )
            ) {
              $get_record_row = $get_record_statement->fetch();
              if ($get_record_statement->rowCount() > 0) {

                $today = date('Y-m-d');
                $exp_time = date('Y-m-d', strtotime($today. ' + 7 days'));
                $set_exp_query = "UPDATE useres SET email_reset_expiration = '$exp_time' WHERE email = '$email'";
                $set_exp_statement = $db->prepare($set_exp_query);
                $set_exp_statement->execute();

                $firstname_record = $get_record_row["firstname"];
                $email_record = $get_record_row["email"];
                $token_record = $get_record_row["token"];
                $email_obj = array(
                  "to" => $email_record,
                  "from" => "no_reply@akurata.lk",
                  "name" => $firstname_record,
                  "subject" => "Reset password [AKURATA.LK]",
                  "token" => $token_record
                );
                if ($Email->sendResetPasswordEmail($email_obj)) {
                  $response = array(
                    "status" => "success",
                    "error" => false,
                    "message" => "Reset password email has sent"
                  );
                  http_response_code(200);
                  echo json_encode($response);
                } else {
                  $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Reset password email failed to send"
                  );
                  http_response_code(400);
                  echo json_encode($response);
                }
              } else {
                $response = array(
                  "status" => "error",
                  "error" => true,
                  "message" => "Account doesn't exist"
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
    }
    function GetUsers() {
      $data = json_decode(file_get_contents("php://input"));
      $id = htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8');
      $per_page = htmlspecialchars($data->per_page, ENT_QUOTES, 'UTF-8');
      $offset = htmlspecialchars($data->offset, ENT_QUOTES, 'UTF-8');
      $search_by = htmlspecialchars($data->search_by, ENT_QUOTES, 'UTF-8');
      $search_keyword = htmlspecialchars($data->search_keyword, ENT_QUOTES, 'UTF-8');

      $search_by_key = '';
      if ($search_by == 0) {
        $search_by_key = 'username';
      } else if ($search_by == 1) {
        $search_by_key = 'firstname';
      } else if ($search_by == 2) {
        $search_by_key = 'lastname';
      } else if ($search_by == 3) {
        $search_by_key = 'email';
      }

      if ( empty($id) ) {
        http_response_code(400);
        echo json_encode(array("message" => "id is undefined."));
      } else {
        $db = new Connect;
        $query = "SELECT COUNT(*) AS is_allowed FROM useres WHERE id = '$id' AND (roleId = 1025 OR roleId = 1026)";

        $statement = $db->prepare($query);
        $statement->execute();
        $row = $statement->fetch();
        $is_allowed = json_encode($row["is_allowed"]);
        $is_allowed = (int)$is_allowed;

        if ($is_allowed == 1) {

          $users_query = "SELECT
          u.id,
          u.username,
          u.firstname,
          u.lastname,
          u.email,
          u.is_email_verified,
          r.label AS roleName
          FROM useres AS u
          LEFT JOIN roles AS r
          ON u.roleId = r.roleId
          WHERE u.$search_by_key LIKE '%$search_keyword%'
          ORDER BY u.id DESC
          LIMIT $per_page
          OFFSET $offset";
          $users_statement = $db->prepare($users_query);
          $users_statement->execute();
          $userData = array();
          while($OutputData=$users_statement->fetch(PDO::FETCH_ASSOC)){
            $userData[$OutputData['id']]=array(
             'id' => $OutputData['id'],
             'username' => $OutputData['username'],
             'firstname' => $OutputData['firstname'],
             'lastname' => $OutputData['lastname'],
             'email' => $OutputData['email'],
             'is_email_verified' => $OutputData['is_email_verified'],
             'roleName' => $OutputData['roleName'],
            );
          }

          $user_count_query = "SELECT COUNT(*) AS record_count FROM useres WHERE $search_by_key LIKE '%$search_keyword%'";
          $user_count_statement = $db->prepare($user_count_query);
          $user_count_statement->execute();
          $user_count_row = $user_count_statement->fetch();

          $userData = array_values($userData);
          $user_data = (object) [
            'status' => 'success',
            'error' => false,
            'record_count'=> $user_count_row['record_count'],
            'records' => $userData
          ];
          http_response_code(200);
          return json_encode($user_data);
        } else {
          http_response_code(403);
          echo json_encode(array("message" => "unauthorized"));
        }
      }
    }
    function GetUser() {
      $data = json_decode(file_get_contents("php://input"));
      $id = htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8');
      $user_id = htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8');

      if (empty($id)) {
        http_response_code(400);
        echo json_encode(array("message" => "unauthorized"));
      } else if (empty($user_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "Required param is missing"));
      } else {
        $db = new Connect;
        $is_allowed_query = "SELECT COUNT(*) AS is_allowed FROM useres WHERE id = '$id' AND (roleId = 1025 OR roleId = 1026)";

        $is_allowed_statement = $db->prepare($is_allowed_query);
        $is_allowed_statement->execute();
        $is_allowed_row = $is_allowed_statement->fetch();
        $is_allowed = json_encode($is_allowed_row["is_allowed"]);
        $is_allowed = (int)$is_allowed;
        
        if ($is_allowed == 1) {
          $statement = $db->prepare("SELECT
          u.id,
          u.username,
          u.firstname,
          u.lastname,
          u.email,
          u.is_email_verified,
          u.token,
          r.label AS roleName,
          r.roleId
          FROM useres AS u
          LEFT JOIN roles AS r
          ON u.roleId = r.roleId
          WHERE u.id = :id");
          try {
            if (
              $statement->execute([
                'id' => $user_id
              ])
            ) {
              $Data = array();
              while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
                $Data[]=array(
                'id'=> $OutputData['id'],
                'username'=> $OutputData['username'],
                'firstname'=> $OutputData['firstname'],
                'lastname'=> $OutputData['lastname'],
                'email'=> $OutputData['email'],
                'is_email_verified'=> $OutputData['is_email_verified'],
                'roleName' => $OutputData['roleName'],
                'roleId' => $OutputData['roleId']
                );
              }
              $Plugin = new Plugin;
              $roleData = $Plugin->roles($db);
              $phoneData = $Plugin->phones($db, $user_id);
              $locationData = $Plugin->location($db, $user_id);

              $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Successfully retreived data",
                "user" => $Data[0],
                "roles" => $roleData,
                "phones" => $phoneData,
                "locations" => $locationData[0]
              );
              http_response_code(200);
              echo json_encode($response);
            }
          } catch (Exception $e) {
            http_response_code(400);
            $db->rollback();
            throw $e;
          }
        } else {
          http_response_code(403);
          echo json_encode(array("message" => "unauthorized"));
        }
      }
    }
    function UpdateUser() {
      $data = json_decode(file_get_contents("php://input"));
      $id = htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8');
      $user_id = htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8');
      $firstname = htmlspecialchars($data->firstname, ENT_QUOTES, 'UTF-8');
      $lastname = htmlspecialchars($data->lastname, ENT_QUOTES, 'UTF-8');
      $is_email_verified = htmlspecialchars($data->is_email_verified, ENT_QUOTES, 'UTF-8');
      $roleId = htmlspecialchars($data->roleId, ENT_QUOTES, 'UTF-8');
      $classes = !empty($data->classes) ? $data->classes : [];
      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        if (empty($id)) {
          http_response_code(400);
          echo json_encode(array("message" => "unauthorized"));
        } else if (empty($user_id)) {
          http_response_code(400);
          echo json_encode(array("message" => "Required param is missing"));
        } else {
          $db = new Connect;
          $is_allowed_query = "SELECT COUNT(*) AS is_allowed FROM useres WHERE id = '$id' AND (roleId = 1025 OR roleId = 1026)";

          $is_allowed_statement = $db->prepare($is_allowed_query);
          $is_allowed_statement->execute();
          $is_allowed_row = $is_allowed_statement->fetch();
          $is_allowed = json_encode($is_allowed_row["is_allowed"]);
          $is_allowed = (int)$is_allowed;
          
          if ($is_allowed == 1) {
            $statement = $db->prepare("UPDATE useres SET firstname = :firstname, lastname = :lastname, roleId = :roleId, is_email_verified = :is_email_verified WHERE id = :user_id");
            try {
              if (
                $statement->execute([
                  'user_id' => $user_id,
                  'firstname' => $firstname,
                  'lastname' => $lastname,
                  'is_email_verified' => $is_email_verified,
                  'roleId' => $roleId
                ])
              ) {
                $this->referClasses($db, $classes, $user_id);
              }
            } catch (Exception $e) {
              http_response_code(400);
              $db->rollback();
              throw $e;
            }
          } else {
            http_response_code(403);
            echo json_encode(array("message" => "unauthorized"));
          }
        }
      }
    }
    function referClasses($db, $classes, $user_id) {
      $length = count($classes);
      $count = 1;
      if (count($classes) > 0) {
        if (!empty($classes[0]->assigned_item_id)) {
          foreach ($classes as $class) {
            $delete_enrollments_query = "DELETE FROM enrollments WHERE item_id = $class->assigned_item_id AND assignee_id = $user_id";
            $delete_enrollments_statement = $db->prepare($delete_enrollments_query);
            $delete_enrollments_statement->execute();
            if ($length == $count) {
              foreach ($classes as $class) {
                $delete_query = "DELETE FROM assigned_categories WHERE post_type_id = $class->post_type_id AND user_id = $user_id";
                $delete_statement = $db->prepare($delete_query);
                $delete_statement->execute();
              }
              $this->insertAssignedCategories($db, $classes, $user_id);
            } else {
              ++$count;
            }
          }
        } else {
          foreach ($classes as $class) {
            $delete_query = "DELETE FROM assigned_categories WHERE post_type_id = $class->post_type_id AND user_id = $user_id";
            $delete_statement = $db->prepare($delete_query);
            $delete_statement->execute();
          }
          $this->insertAssignedCategories($db, $classes, $user_id);
        }
      } else {
        $delete_query = "DELETE FROM assigned_categories WHERE post_type_id = 1 AND user_id = $user_id";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->execute();
        $response = array(
          "status" => "success",
          "error" => false,
          "message" => "Successfully updated without assigning categories"
        );
        http_response_code(200);
        echo json_encode($response);
      }
    }
    function insertAssignedCategories ($db, $classes, $user_id) {
      $length = count($classes);
      $count = 1;
      foreach ($classes as $class) {
        $query = "INSERT INTO assigned_categories (post_type_id, category_id, user_id, fee, demo_video_src) VALUES ($class->post_type_id, $class->id, $user_id, '$class->fee', '$class->src')";
        $statement = $db->prepare($query);
        $statement->execute();
        if ($length == $count) {
          $response = array(
            "status" => "success",
            "error" => false,
            "message" => "Successfully updated by assigning categories"
          );
          http_response_code(200);
          echo json_encode($response);
        } else {
          $count++;
        }
      }
    }
    function getAssignedClasses() {
      $data = json_decode(file_get_contents("php://input"));
      $user_id = htmlspecialchars($data->user_id, ENT_QUOTES, 'UTF-8');
      $post_type_id = htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8');

      if (empty($user_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "Required param is missing"));
      } else if (empty($post_type_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "Required param is missing"));
      } else {
      $db = new Connect;
        $statement = $db->prepare("SELECT * FROM assigned_categories WHERE post_type_id = :post_type_id AND user_id = :user_id");
        try {
          if (
            $statement->execute([
              'user_id' => $user_id,
              'post_type_id' => $post_type_id,
            ])
          ) {
            $Data = array();
            while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
              $Data[]=array(
              'id'=> $OutputData['id'],
              'post_type_id'=> $OutputData['post_type_id'],
              'category_id'=> $OutputData['category_id'],
              'user_id'=> $OutputData['user_id'],
              'fee'=> $OutputData['fee'],
              'src'=> $OutputData['demo_video_src']
              );
            }

            $response = array(
              "status" => "success",
              "error" => false,
              "message" => "Successfully retreived data",
              "cats" => $Data
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
    function getAvailableClasses() {
      $data = json_decode(file_get_contents("php://input"));
      $post_type_id = htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8');
      $category_id = htmlspecialchars($data->category_id, ENT_QUOTES, 'UTF-8');
      
      $username = !empty($data->username) ? htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8') : NULL;
      $Validations = new Validations;

      if ($Validations->tokenValidate()) {
        if (empty($category_id)) {
          http_response_code(400);
          echo json_encode(array("message" => "Required param is missing"));
        } else if (empty($post_type_id)) {
          http_response_code(400);
          echo json_encode(array("message" => "Required param is missing"));
        } else {
          $db = new Connect;
          $query = "SELECT
          a.id AS id,
          a.post_type_id AS post_type_id,
          a.category_id AS category_id,
          a.user_id AS user_id,
          a.fee AS fee, a.demo_video_src AS demo_video_src,
          u.firstname AS firstname,
          u.lastname AS lastname,
          u.username AS username,
          l.city_id AS city_id,
          ci.city AS city
          FROM assigned_categories AS a
          LEFT JOIN useres AS u
          ON u.id = a.user_id
          LEFT JOIN users_location AS l
          ON u.id = l.user_id
          LEFT JOIN cities AS ci
          ON l.city_id = ci.id
          WHERE a.post_type_id = :post_type_id AND a.category_id = :category_id";
          $statement = $db->prepare($query);
          try {
            if (
              $statement->execute([
                'category_id' => $category_id,
                'post_type_id' => $post_type_id,
              ])
            ) {
              $Data = array();
              while($OutputData=$statement->fetch(PDO::FETCH_ASSOC)){
                $Data[]=array(
                'id'=> $OutputData['id'],
                'post_type_id'=> $OutputData['post_type_id'],
                'category_id'=> $OutputData['category_id'],
                'user_id'=> $OutputData['user_id'],
                'fee'=> $OutputData['fee'],
                'src'=> $OutputData['demo_video_src'],
                'firstname'=> $OutputData['firstname'],
                'lastname'=> $OutputData['lastname'],
                'username'=> $OutputData['username'],
                'city'=> $OutputData['city']
                );
              }

              $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Successfully retreived data",
                "classes" => $Data
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
  }
  return $User = new User;
?>