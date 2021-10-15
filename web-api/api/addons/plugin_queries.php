<?php
  class Plugin {
    function roles($db) {
      $role_query = "SELECT * FROM roles";
      $role_statement = $db->prepare($role_query);
      $role_statement->execute();
      $roleData = array();
      
      while($OutputData=$role_statement->fetch(PDO::FETCH_ASSOC)){
        $roleData[]=array(
        'id'=> $OutputData['id'],
        'roleName' => $OutputData['label'],
        'roleId' => $OutputData['roleId']
        );
      }
      return $roleData;
    }
    function phones($db, $id) {
      $phone_query = "SELECT * FROM users_phone WHERE user_id = $id";
      $phone_statement = $db->prepare($phone_query);
      $phone_statement->execute();
      $phoneData = array();
      
      while($OutputData=$phone_statement->fetch(PDO::FETCH_ASSOC)){
        $phoneData[]=array(
        'id'=> $OutputData['id'],
        'user_id'=> $OutputData['user_id'],
        'user_phone' => $OutputData['user_phone'],
        'is_parent' => $OutputData['is_parent']
        );
      }
      return $phoneData;
    }
    function location($db, $id) {
      $location_query = "SELECT
      l.id AS id,
      l.address AS address,
      c.country AS country,
      s.state AS state,
      d.district AS district,
      ci.city AS city
      FROM users_location AS l
      LEFT JOIN countries AS c
      ON l.country_id = c.id
      LEFT JOIN states AS s
      ON l.state_id = s.id
      LEFT JOIN districts AS d
      ON l.district_id = d.id
      LEFT JOIN cities AS ci
      ON l.city_id = ci.id
      WHERE l.user_id = $id";
      $location_statement = $db->prepare($location_query);
      $location_statement->execute();
      $locationData = array();
      
      while($OutputData=$location_statement->fetch(PDO::FETCH_ASSOC)){
        $locationData[]=array(
        'id'=> $OutputData['id'],
        'address'=> $OutputData['address'],
        'country' => $OutputData['country'],
        'state' => $OutputData['state'],
        'district' => $OutputData['district'],
        'city' => $OutputData['city']
        );
      }
      return $locationData;
    }
  }
  return $Plugin = new Plugin;
?>