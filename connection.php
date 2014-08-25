<?php
$dbhost = 'localhost';
$dbname = 'airsamples';
$dbuser = 'user';
$dbpass = 'pass';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $conn);
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
try { $db = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8", $dbuser, $dbpass, $options); } 
catch(PDOException $ex){ die("Failed to connect to the database: " . $ex->getMessage());} 
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
header('Content-Type: text/html; charset=utf-8'); 
?>