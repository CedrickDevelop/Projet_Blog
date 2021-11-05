<?php

session_start();

if (!isset($_SESSION['email'])){
  header('Location:login.php');
}

  // Connexion à la base de donnée
  require_once('../DBconnect.php');
  
  // / Récupération des articles
$sql = (
  'SELECT 
      posts.id,
      posts.created_at,
      DATE_FORMAT(posts.created_at, "%e.%c.%y") as created_at,
      posts.title,
      -- posts.contents,
      authors.firstname,
      authors.lastname,
      categories.name
  FROM posts
  INNER JOIN  categories on posts.category_id = categories.id
  INNER JOIN  authors on posts.author_id = authors.id
  ORDER BY posts.id DESC
  ' );

$query = $cnx->prepare($sql);
$query->execute();
$articles = $query->fetchAll();

// var_dump($_SERVER);
// var_dump($_SERVER['DOCUMENT_ROOT']);
  
  // Chargement de la page
    $administration = true;
    $title = "Administration";
    $template = './admin.phtml';

  include('../gabarit.phtml');

 




?>
