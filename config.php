<?php

session_start();

$base = 'http://localhost/devsbookOOP';

$dbName = 'devsbook';
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';

$pdo = new PDO("mysql:dbname=".$dbName.";host=".$dbHost,$dbUser,$dbPass);


