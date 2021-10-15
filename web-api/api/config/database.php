<?php
    require_once __DIR__ . '/core.php';
    require_once __DIR__ . '/../addons/validations.php';
    require_once __DIR__ . '/../addons/enc.php';
    require_once __DIR__ . '/../controllers/email/index.php';
    require_once __DIR__ . '/../addons/plugin_queries.php';
    class Connect extends PDO
    {
        public function __construct()
        {
            // parent::__construct("mysql:host=localhost;dbname=akurata",'root','', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            parent::__construct("mysql:host=localhost;dbname=audiblep_lms",'audiblep_lms','sandamal53', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        }
    }
?>