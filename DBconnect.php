<?php

  define('HOST', 'localhost');
  define('DB_NAME', 'blog_3wa');
  define('DB_ID', 'root');
  define('DB_MDP', '');


  try {

    $cnx = new PDO('mysql:host=' . HOST . ';dbname='.DB_NAME , DB_ID , DB_MDP);
    $cnx->exec('SET NAMES UTF8');
    $cnx->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } 
  catch (Exception $e) {
    echo 'Problème de connexion : ' . $e->getMessage();
  }

  // Pour que DATE_FORMAT renvoie la date en français
  $cnx->query("SET lc_time_names = 'fr_FR';");




?>