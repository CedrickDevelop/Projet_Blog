<?php

require_once('./DBconnect.php');



// Récupération des articles
$sql = (
  'SELECT 
      posts.id,
      posts.title,
      posts.contents,
      posts.created_at,
      categories.name,
      authors.firstname,
      authors.lastname
  FROM posts
  INNER JOIN  categories on posts.category_id = categories.id
  INNER JOIN  authors on posts.author_id = authors.id
  WHERE posts.id = :id ' );

$query = $cnx->prepare($sql);
$query->bindValue(':id', $_GET['id']);
// $query->bindValue(':id', $id);
$query->execute();
$articles = $query->fetchAll();


// Récupération des commentaires
$sql = (
  'SELECT 
      comments.id,
      comments.nickname,
      comments.content,
      DATE_FORMAT(comments.created_at, "%W %e %M %Y") as created_at
  FROM comments
  WHERE comments.post_id = :id 
  ORDER BY comments.created_at DESC
  ' );

$query = $cnx->prepare($sql);
$query->bindValue(':id', $_GET['id']);
// $query->bindValue(':id', $id);
$query->execute();
$comments = $query->fetchAll();



// Récupération du nombre de commentaires
$sql = (
  'SELECT 
      COUNT(comments.id) as nb
  FROM comments
  WHERE comments.post_id = :id 
  ' );

$query = $cnx->prepare($sql);
$query->bindValue(':id', $_GET['id']);
$query->execute();
$comments_number = $query->fetch();



  // Affichage de la page d'accueil

  $title = "article".$articles[0]['title'];
  $template = './show_article.phtml';

  // Affichage du gabarit
  include('./gabarit.phtml');

?>