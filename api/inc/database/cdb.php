<?php


function cdb(){

    static $db;

    if(!isset($db)) {
 
        $config = parse_ini_file('configDB.ini');
        $db = mysqli_connect($config['HOST'],$config['MYSQL_USER'],$config['MYSQL_PASSWORD'],$config['DB_NAME']);
    }

    if($db === false) {

        return mysqli_connect_error(); 
    }

    return $db;
}

?>