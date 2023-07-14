<?php
  const DBHOST = 'phpmyadmin.viaduc.fr';        // Database Hostname
  const DBUSER = 'h20465';             // MySQL Username
  const DBPASS = '8parDX3I';                 // MySQL Password
  const DBNAME = 'h20465_amyazvocab';  // MySQL Database name

  // Data Source Network
  $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . '';

  // Connection Variable
  $conn = null;

  // Connect Using PDO (PHP Data Output)
  try {
    $conn = new PDO($dsn, DBUSER, DBPASS);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die('Error : ' . $e->getMessage());
  }
?>