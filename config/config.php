<?php
session_start();

define('BASE_URL','http://localhost/IT34B-1');

define('DB_HOST','localhost');
define('DB_NAME', 'it34b_lab_db2');
define('DB_USER','root');
define('DB_PASS','');

try{
    $pdo =new PDO(
        "mysql:host" . DB_HOST . ";dbname=" .DB_NAME, DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}catch(PDOException $e){
        die("Connection failed: " . $e->getMessage());
} 
?>