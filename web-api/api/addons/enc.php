<?php
  class Encrypt {
    function password($str) {
      return password_hash($str, PASSWORD_DEFAULT);
    }
    function verify($str, $hash) {
      return password_verify($str, $hash);
    }
    function token($secret) {
      $uniqid = uniqid();
      return hash_hmac('sha256', $uniqid, $secret);
    }
  }
  return $Encrypt = new Encrypt;
?>