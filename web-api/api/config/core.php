<?php
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json');
    // header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>