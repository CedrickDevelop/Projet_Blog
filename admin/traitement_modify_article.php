<?php

  // Connexion à la base de donnée
  require_once('../DBconnect.php');
  
// var_dump($_POST);


  // RECUPERATION DES IDS AUTEUR ET CATEGORIE***************************
 $author = preg_split("/[\s,]+/",$_POST['select_author']);
 $author_firstname = $author[0];
 $author_lastname = $author[1];

 $category = $_POST['select_category'];

 $id = $_POST['id'];

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

// var_dump($author, $category);


// NETTOYAGE DES DONNEES***************************
  $title = filter_var($_POST['write_title'], FILTER_SANITIZE_STRING );
  $content = filter_var($_POST['write_content'], FILTER_SANITIZE_STRING );


// MISE A JUOR DES INFORMATIONS EN BDD***************************
$sql = (
  " UPDATE  posts 
    SET  title = '{$title}', 
         contents = '{$content}'
        --  author_id = '{$author}',
        --  category_id = '{$category}'
    WHERE id = '{$id}'
");

$query = $cnx->prepare($sql);
$query->execute();

// header("location:admin.php");
// exit();

?>