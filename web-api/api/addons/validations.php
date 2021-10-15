<?php
  $response;
  class Validations {
    function isNic($str) {
      if(preg_match('/^([0-9]{9}[x|X|v|V]|[0-9]{12})+$/m', $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid NIC number or format"
        ];
      }
      return $response;
    }
    function isPhone($str) {
      if(preg_match('/^\d{9}+$/', $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid phone number or format"
        ];
      }
      return $response;
    }
    function isEmail($str) {
      if(filter_var($str, FILTER_VALIDATE_EMAIL)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid email address or format"
        ];
      }
      return $response;
    }
    function isName($str) {
      if(preg_match('/^[A-Za-z]+$/', $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid name or format"
        ];
      }
      return $response;
    }
    function isUsername($str) {
      if(preg_match('/^[a-zA-Z0-9]+$/', $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid username or format"
        ];
      }
      return $response;
    }
    function isInt($int) {
      if(filter_var($int, FILTER_VALIDATE_INT)) {
        $response = (object) [
          "valid" => true,
          "message" => $int
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid value"
        ];
      }
      return $response;
    }
    function isNameWithSpaces($str) {
      if(preg_match('/^[A-Za-z\s ]+$/', $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid name or format"
        ];
      }
      return $response;
    }
    function isAddress($str) {
      if(preg_match("/^[a-zA-Z0-9\s,'-]*$/", $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid address format"
        ];
      }
      return $response;
    }
    function strongPassword($str) {
      // if(preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z\s*!@#$%^&()]{8,}$/", $str)) {
      if(preg_match("/^.{4,}$/", $str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          // "message" => "Password should contain at least one digit, one lower case, one upper case and minimum length is 8. ex: acRo9@32"
          "message" => "Password should contain at least 4 characters"
        ];
      }
      return $response;
    }
    function isBool($str) {
      if(is_bool($str)) {
        $response = (object) [
          "valid" => true,
          "message" => $str
        ];
      } else {
        $response = (object) [
          "valid" => false,
          "message" => "Invalid boolean"
        ];
      }
      return $response;
    }
    // function tokenValidate($username) {
    //   $data = json_decode(file_get_contents("php://input"));
    //   $headers = apache_request_headers();
    //   $local = true;
    //   $token = '';
    //   if (!empty($data->token)) {
    //     $token = $data->token;
    //   } else if ($local == true) {
    //     $token = $headers['authorization'];
    //     $token = str_replace("Bearer ", "", $token);
    //   } else {
    //     $headerCookies = explode('; ', getallheaders()['Cookie']);
    //     $cookies = array();
    //     foreach($headerCookies as $itm) {
    //       list($key, $val) = explode('=', $itm,2);
    //       $cookies[$key] = $val;
    //     }
    //     if (!empty($cookies['token'])) {
    //       $token = $cookies['token'];
    //     }
    //   }

    //   if (empty($token)) {
    //     http_response_code(400);
    //     echo json_encode(array("message" => "Token is missing"));
    //     return false;
    //   } else if (empty($username)) {
    //     http_response_code(400);
    //     echo json_encode(array("message" => "Username is missing"));
    //     return false;
    //   } else {
    //     $db = new Connect;
    //     $query = "SELECT COUNT(*) AS allowed FROM useres WHERE username = :username AND token = :token";
    //     $statement = $db->prepare($query);
    //     try {
    //       if (
    //         $statement->execute([
    //           'username' => $username,
    //           'token' => $token
    //         ])
    //       ) {
    //         $row = $statement->fetch();
    //         $allowed = $row["allowed"];
    //         if ($allowed > 0) {
    //           return true;
    //         } else {
    //           return (object) [
    //             "valid" => false,
    //             "message" => "Unauthorized"
    //           ];
    //         }
    //       }
    //     } catch (Exception $e) {
    //       $db->rollback();
    //       throw $e;
    //     }
    //   }
    //   return $token;
    // }
    function tokenValidate() {
      $data = json_decode(file_get_contents("php://input"));
      $data_username = !empty($data->username) ? htmlspecialchars($data->username, ENT_QUOTES, 'UTF-8') : null;
      $data_token = !empty($data->token) ? htmlspecialchars($data->token, ENT_QUOTES, 'UTF-8') : null;
      $token = null;
      $username = null;
      if (isset($_COOKIE['token']) && isset($_COOKIE['username'])) {
        $token = $_COOKIE['token'];
        $username = $_COOKIE['username'];
      } else {
        $token = $data_token;
        $username = $data_username;
      }
      if (!empty($token) && !empty($username)) {
        if (empty($token)) {
          http_response_code(400);
          echo "Token is missing";
          return false;
        } else if (empty($username)) {
          http_response_code(400);
          echo "Username is missing";
          return false;
        } else {
          $db = new Connect;
          $query = "SELECT COUNT(*) AS allowed FROM useres WHERE username = :username AND token = :token";
          $statement = $db->prepare($query);
          try {
            if (
              $statement->execute([
                'username' => $username,
                'token' => $token
              ])
            ) {
              $row = $statement->fetch();
              $allowed = $row["allowed"];
              if ($allowed > 0) {
                return true;
              } else {
                http_response_code(403);
                echo "Unauthorized";
                return false;
              }
            }
          } catch (Exception $e) {
            $db->rollback();
            throw $e;
          }
        }
        return $token;
      } else {
        http_response_code(403);
        echo "Unauthorized";
        return false;
      }
    }
  }
  return $Validations = new Validations;
?>