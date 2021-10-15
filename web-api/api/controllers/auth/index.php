<?php
  require_once __DIR__ . '/../../config/core.php';
  require_once __DIR__ . '/../../config/database.php';
  class Auth {
    function Register() {
      $data = json_decode(file_get_contents("php://input"));
      $username = htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8');
      $firstname = htmlspecialchars($data->firstname, ENT_QUOTES, 'UTF-8');
      $lastname = htmlspecialchars($data->lastname, ENT_QUOTES, 'UTF-8');
      $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
      $password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');
      $studentPhone = htmlspecialchars($data->studentPhone, ENT_QUOTES, 'UTF-8');
      $parentPhone = htmlspecialchars($data->parentPhone, ENT_QUOTES, 'UTF-8');
      $sameAsStudent = $data->sameAsStudent;
      $gender = htmlspecialchars($data->gender, ENT_QUOTES, 'UTF-8');
      $dob = htmlspecialchars($data->dob, ENT_QUOTES, 'UTF-8');
      $address = htmlspecialchars($data->address, ENT_QUOTES, 'UTF-8');
      $state = htmlspecialchars($data->state, ENT_QUOTES, 'UTF-8');
      $district = htmlspecialchars($data->district, ENT_QUOTES, 'UTF-8');
      $city = htmlspecialchars($data->city, ENT_QUOTES, 'UTF-8');
      $post_type_id = !empty($data->post_type_id) ? htmlspecialchars($data->post_type_id, ENT_QUOTES, 'UTF-8') : NULL;
      $cat_id = !empty($data->cat_id) ? htmlspecialchars($data->cat_id, ENT_QUOTES, 'UTF-8') : NULL;
      $child_cat_id = !empty($data->child_cat_id) ? htmlspecialchars($data->child_cat_id, ENT_QUOTES, 'UTF-8') : NULL;
      $child_cat_id2 = !empty($data->child_cat_id2) ? htmlspecialchars($data->child_cat_id2, ENT_QUOTES, 'UTF-8') : NULL;
      // $school = htmlspecialchars($data->school, ENT_QUOTES, 'UTF-8');
      // $grade = htmlspecialchars($data->grade, ENT_QUOTES, 'UTF-8');

      $country = 1; // for Sri Lanka
      
      $Validations = new Validations;
      $Encrypt = new Encrypt;
      $Email = new Email;

      if (empty($username) && empty($firstname) && empty($lastname) && empty($email) && empty($password) && empty($studentPhone) && empty($parentPhone) && empty($sameAsStudent) && empty($gender) && empty($dob) && empty($address) && empty($state) && empty($district) && empty($city)) {
        http_response_code(400);
        echo json_encode(array("message" => "required field is missing"));
      }
      else if (!$Validations->isUsername($username)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isUsername($username)->message));
      }
      else if (!$Validations->isName($firstname)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isName($firstname)->message));
      }
      else if (!$Validations->strongPassword($password)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->strongPassword($password)->message));
      }
      else if (!$Validations->isPhone($studentPhone)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isPhone($studentPhone)->message));
      }
      else if (!$Validations->isPhone($parentPhone)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isPhone($parentPhone)->message));
      }
      else if (!$Validations->isBool($sameAsStudent)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isBool($sameAsStudent)->message));
      }
      else if (!$Validations->isInt($gender)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($gender)->message));
      }
      else if (!$Validations->isInt($state)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($state)->message));
      }
      else if (!$Validations->isInt($district)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($district)->message));
      }
      else if (!$Validations->isInt($city)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isInt($city)->message));
      }
      else {
        $db = new Connect;
        $statement_is_email = $db->prepare("SELECT COUNT(*) AS exist FROM useres WHERE email = :email");
        $statement_is_username = $db->prepare("SELECT COUNT(*) AS exist FROM useres WHERE username = :username");
        try {
          if (
            $statement_is_email->execute(['email' => $email]) && $statement_is_username->execute(['username' => $username])
          ) {
            $row_is_email = $statement_is_email->fetch();
            $row_is_username = $statement_is_username->fetch();
            $is_email_exist = json_encode($row_is_email["exist"]);
            $is_username_exist = json_encode($row_is_username["exist"]);
            $is_email_exist = (int)$is_email_exist;
            $is_username_exist = (int)$is_username_exist;

            if ($is_email_exist == 1) {
              http_response_code(401);
              echo json_encode(array("message" => "Account already exists"));
            } else if ($is_username_exist == 1) {
              http_response_code(402);
              echo json_encode(array("message" => "Username already exists. Try another."));
            } else {
              $statement = $db->prepare("INSERT INTO useres (username, firstname, lastname, email, created_time, password)
              VALUES (:username, :firstname, :lastname, :email, :created_time, :password)");
              $encrypted_password = $Encrypt->password($password);
              $created_time = date('Y-m-d H:i:s');
              try {
                if (
                  $statement->execute(
                    [
                      'username' => $username,
                      'firstname' => $firstname,
                      'lastname' => $lastname,
                      'email' => $email,
                      'created_time' => $created_time,
                      'password' => $encrypted_password
                    ]
                  )
                ) {
                  $last_id = $db->lastInsertId();
                  $meta_statement = $db->prepare("INSERT INTO users_meta (user_id, user_gender, user_dob, edu_post_type_id, edu_cat_id, edu_cat2_id, edu_cat3_id) VALUES (:user_id, :user_gender, :user_dob, :post_type_id, :cat_id, :child_cat_id, :child_cat_id2)");
                  $location_statement = $db->prepare("INSERT INTO users_location (user_id, country_id, state_id, district_id, city_id, address)
                  VALUES (:user_id, :country, :state, :district, :city, :address)");
                  $parent_phone_statement = $db->prepare("INSERT INTO users_phone (user_id, user_phone, is_parent) VALUES (:user_id, :parentPhone, :is_parent)");
                  $student_phone_statement = $db->prepare("INSERT INTO users_phone (user_id, user_phone, is_parent) VALUES (:user_id, :studentPhone, :is_parent)");
                  try {
                    if (
                      $meta_statement->execute([
                        'user_id' => $last_id,
                        'user_gender' => $gender,
                        'user_dob' => $dob,
                        'post_type_id' => $post_type_id,
                        'cat_id' => $cat_id,
                        'child_cat_id' => $child_cat_id,
                        'child_cat_id2' => $child_cat_id2,
                      ]) &&
                      $location_statement->execute([
                        'user_id' => $last_id,
                        'country' => $country,
                        'state' => $state,
                        'district' => $district,
                        'city' => $city,
                        'address' => $address
                      ]) &&
                      $student_phone_statement->execute([
                        'user_id' => $last_id,
                        'studentPhone' => $studentPhone,
                        'is_parent' => 0
                      ]) &&
                      $parent_phone_statement->execute([
                        'user_id' => $last_id,
                        'parentPhone' => $parentPhone,
                        'is_parent' => 1
                      ])
                    ) {
                      $email_obj = array(
                        "to" => $email,
                        "from" => "no_reply@akurata.lk",
                        "name" => $firstname,
                        "subject" => "Activate your account [AKURATA.LK]",
                        "token" => $encrypted_password
                      );
                      if ($Email->sendVerificationEmail($email_obj)) {
                        $response = array(
                          "status" => "success",
                          "error" => false,
                          "message" => "Account is successfully created and verification email has sent"
                        );
                        http_response_code(200);
                        echo json_encode($response);
                      } else {
                        $response = array(
                          "status" => "error",
                          "error" => true,
                          "message" => "Account is successfully created but verification email failed to send"
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
              } catch (Exception $e) {
                http_response_code(400);
                $db->rollback();
                throw $e;
              }
            }
          }
        } catch (Exception $e) {
          http_response_code(400);
          $db->rollback();
          throw $e;
        }
      }
    }
    function Login() {
      $data = json_decode(file_get_contents("php://input"));
      $username = htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8');
      $password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');
      
      $Validations = new Validations;
      $Encrypt = new Encrypt;

      if (empty($username)) {
        http_response_code(400);
        echo json_encode(array("message" => "Please enter username."));
      } else if (!$Validations->isUsername($username)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isUsername($username)->message));
      } else if (empty($password)) {
        http_response_code(400);
        echo json_encode(array("message" => "Please enter password"));
      } else {
        $db = new Connect;
        $get_pw_statement = $db->prepare("SELECT `password` FROM useres WHERE username = :username");
        try {
          if (
            $get_pw_statement->execute(
              [
                'username' => $username
              ]
            )
          ) {
            $get_pw_row = $get_pw_statement->fetch();
            if ($get_pw_statement->rowCount() > 0) {
              $hash = $get_pw_row["password"];
              $is_verified = $Encrypt->verify($password, $hash);
              if ($is_verified > 0) {
                $token = $this->Token($password);
                $set_token_statement = $db->prepare("UPDATE useres SET token = :token WHERE username = :username");
                try {
                  if (
                    $set_token_statement->execute(
                      [
                        'token' => $token,
                        'username' => $username
                      ]
                    )
                  ) {
                    $response = array(
                      "status" => "success",
                      "error" => false,
                      "message" => "Successfully logged in",
                      "token" => $token
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
    function checkToken() {
      $data = json_decode(file_get_contents("php://input"));
      $username = htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8');
      $token = htmlspecialchars($data->token, ENT_QUOTES, 'UTF-8');
      
      $Validations = new Validations;

      if (empty($username)) {
        http_response_code(400);
        echo json_encode(array("message" => "Please enter username."));
      } else if (!$Validations->isUsername($username)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isUsername($username)->message));
      } else {
        $db = new Connect;
        $statement3 = $db->prepare("SELECT COUNT(*) AS access FROM useres WHERE username = :username AND token = :token");
        try {
          if (
            $statement3->execute(
              [
                'username' => $username,
                'token' => $token
              ]
            )
          ) {
            $row = $statement3->fetch();
            $access = json_encode($row["access"]);
            $access = (int)$access;
            if ($access == 0) {
              $response = array(
                "status" => "error",
                "error" => true,
                "message" => "Unauthorized"
              );
              http_response_code(403);
              echo json_encode($response);
            } else if ($access == 1) {
              $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Authorized"
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
    }
    function Token($password) {
      $Encrypt = new Encrypt;
      return $Encrypt->token($password);
    }
    function AppAuth() {
      $data = json_decode(file_get_contents("php://input"));
      $username = htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8');
      $token_key = htmlspecialchars($data->token, ENT_QUOTES, 'UTF-8');
      
      $Validations = new Validations;
      $Encrypt = new Encrypt;

      if (empty($username)) {
        http_response_code(400);
        echo json_encode(array("message" => "Please enter username."));
      } else if (!$Validations->isUsername($username)->valid) {
        http_response_code(400);
        echo json_encode(array("message" => $Validations->isUsername($username)->message));
      } else if (empty($token_key)) {
        http_response_code(400);
        echo json_encode(array("message" => "Please enter password"));
      } else {
        $db = new Connect;
        $get_pw_statement = $db->prepare("SELECT `token` FROM useres WHERE username = :username");
        $get_user_id_statement = $db->prepare("SELECT `id` FROM useres WHERE username = '$username'");
        $get_user_id_statement->execute();
        $get_user_id_row = $get_user_id_statement->fetch();
        $user_id = $get_user_id_row["id"];
        try {
          if (
            $get_pw_statement->execute(
              [
                'username' => $username
              ]
            )
          ) {
            $get_pw_row = $get_pw_statement->fetch();
            if ($get_pw_statement->rowCount() > 0) {
              $hash = $get_pw_row["token"];
              $is_verified = $token_key == $hash;
              if ($is_verified > 0) {
                $response = array(
                  "status" => "success",
                  "error" => false,
                  "message" => "Successfully logged in",
                  "token" => $token_key,
                  "user_id" => $user_id
                );
                http_response_code(200);
                echo json_encode($response);
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
  }
  return $Auth = new Auth;
?>