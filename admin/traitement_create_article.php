<?php

session_start();

if (!isset($_SESSION['email'])){
  header('Location:login.php');
}

  // Connexion à la base de donnée
  require_once('../DBconnect.php');

// RECUPERATION DES IDS AUTEUR ET CATEGORIE***************************
 $author = preg_split("/[\s,]+/",$_POST['select_author']);
 $author_firstname = $author[0];
 $author_lastname = $author[1];

 $category = $_POST['select_category'];

 // SELECT Categories
 $sql = ("SELECT id FROM categories WHERE name= '{$category}' " );
$query = $cnx->prepare($sql);
$query->execute();
$category = $query->fetch();
 // SELECT Author
 $sql = ("SELECT id FROM authors WHERE firstname = '{$author_firstname}' AND lastname = '{$author_lastname}' " );
$query = $cnx->prepare($sql);
$query->execute();
$author = $query->fetch();


// NETTOYAGE DES DONNEES***************************
  $title = filter_var($_POST['write_title'], FILTER_SANITIZE_STRING );
  $content = filter_var($_POST['write_content'], FILTER_SANITIZE_STRING );


// INSERTION ARTICLES EN BDD***************************
$sql = (
  'INSERT INTO  posts  (title, contents, author_id, category_id)
   VALUES (:title, :content, :author_id, :category_id)');

$query = $cnx->prepare($sql);
$query->bindValue(':title', $title, PDO::PARAM_STR);
$query->bindValue(':content', $content, PDO::PARAM_STR);
$query->bindValue(':author_id', $author, PDO::PARAM_INT);
$query->bindValue(':category_id', $category, PDO::PARAM_INT);
$query->execute();

header("location:admin.php");
exit();

?>