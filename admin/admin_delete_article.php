<?php

session_start();

if (!isset($_SESSION['email'])){
  header('Location:login.php');
}

  // Connexion à la base de donnée
  require_once('../DBconnect.php');
  


// Initialisation de l'ID
if (!empty($_GET['id'])){
  $id = $_GET['id'];
} else {
  $id = $_POST['submit'];
}


// if ( (isset($_POST['submit']) && (!empty($_POST))) ) {
// // MISE A JOUR DES INFORMATIONS EN BDD***************************
  $sql = (
    " DELETE FROM posts 
      WHERE id = '{$id}'
  ");
  $query = $cnx->prepare($sql);
  $query->execute();

  header("location:admin.php");
  exit();
// } 


// *********************************************************************
// CHARGEMENT DE PAGE RECUPERATION DES INFORMATIONS DE L'ARTICLE *******
// *********************************************************************
  // $sql = (
  //   "SELECT 
  //       posts.id,
  //       categories.name,
  //       authors.firstname,
  //       authors.lastname,
  //       posts.title,
  //       posts.contents,
  //       posts.created_at
  //   FROM posts
  //   INNER JOIN  categories on posts.category_id = categories.id
  //   INNER JOIN  authors on posts.author_id = authors.id
  //   WHERE posts.id='{$id}'
  //   " );
  // $query = $cnx->prepare($sql);
  // $query->execute();
  // $article = $query->fetch(); 

  
  // // Chargement de la page
  //   $administration = true;
  //   $title = "Administration creer un article";
  //   $template = './admin_delete_article.phtml';

  // include('../gabarit.phtml');

 




?>