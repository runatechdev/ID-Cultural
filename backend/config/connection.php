<?php
$dbPath = __DIR__ . '/../db/database.sqlite'; 
$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
