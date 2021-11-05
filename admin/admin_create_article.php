<?php

session_start();

if (!isset($_SESSION['email'])){
  header('Location:login.php');
}

  // Connexion à la base de donnée
  require_once('../DBconnect.php');
  
// $messageFormulaire = ""; 
// $message_write_title = "";
// $message_write_content ="";

// CHARGEMENT DES DoNNEES  FORMULAIRE**************************************  
// VERIFICATIONS DE BASE***************************
// if ( isset($_POST['submit']) 
//   && ((empty($_POST['write_title'])) 
//   || (empty($_POST['write_content']))) ) {

//   $messageFormulaire = "Veuillez remplir tous les champs du formulaire";
//   $messageFormulaire = "On est au bloc 1";
// } 

// else if  ( isset($_POST['submit']) 
//         && ((strlen($_POST['write_title']) > 100) 
//         ||  (strlen($_POST['write_title']) < 10)) 
//         ) {
//   $message_write_title = "Votre titre doit contenir au minimum 10 caractères et maximum 100 caractères";
// }

// else if  ( isset($_POST['submit']) 
//           && ((strlen($_POST['write_content']) > 1000) 
//           || (strlen($_POST['write_content']) < 100)) 
//           ) {
//   $message_write_content = "Votre texte doit contenir au minimum 100 caractères et maximum 1000 caractères";
// }

$postTitle = '';
$content = '';
$category_id = '';
$author_id = '';

// Si TOUT EST OK ON ENVOI EN BDD **************************************  
if ( (isset($_POST['submit']) && (!empty($_POST))) )  {    

  // VERIFICATIONS DE BASE SELON CORRECTION PROF***************************
  function checkData($val){
    $val = trim($val);
    $val = stripslashes($val);
    $val = htmlspecialchars($val);
    $val = filter_var($val, FILTER_SANITIZE_STRING );
    return $val;
  }
  // Recuperation des informations formulaire
  $postTitle = checkData($_POST['write_title']);
  $content = checkData($_POST['write_content']);
  $category = checkData($_POST['select_category']);
  $author = checkData($_POST['select_author']);

  // Création du tableau des erreurs
  $errors= [];

  // on vérifie que les champs soient bien remplis
  if (empty($postTitle)) {
      $error['postTitle'] = 'Le titre est obligatoire';
  }
  if (empty($content)) {
      $error['content'] = 'Le contenu est obligatoire';
  } 
  else if(strlen($content) < 100 || strlen($content) > 10000) {
      $error['content'] = 'Le contenu doit faire au moins 100 caractères et au plus 10000 caractères.';
  }
  else if(strlen($postTitle) < 10 || strlen($postTitle) > 1000) {
      $error['content'] = 'Le contenu doit faire au moins 100 caractères et au plus 10000 caractères.';
  }


  // VERIFICATIONS DES AUTEURS ***************************
  if (!ctype_alpha($author)){
 
    $author = preg_split("/[\s,]+/",$author);
    $author_firstname = $author[0];
    $author_lastname = $author[1];

      // SELECT Author
    $sql = ("SELECT id FROM authors WHERE firstname = '{$author_firstname}' AND lastname = '{$author_lastname}' " );
    $query = $cnx->prepare($sql);
    $query->execute();
    $author = $query->fetch();
  } else {
    $error['author'] = 'Vérifier le nom de l\'auteur.';
    var_dump($author);
  }

  // VERIFICATIONS DE CATEGORIE***************************
  if (ctype_alpha($category)){
    $sql = ("SELECT id FROM categories WHERE name= '{$category}' " );
    $query = $cnx->prepare($sql);
    $query->execute();
    $category = $query->fetch();

  } else {
    $error['category'] = 'Vérifier le nom de la categorie.';
  }  
  
  // INSERTION ARTICLES EN BDD***************************
  if (empty($error)) {
    $sql = (
      'INSERT INTO  posts  (title, contents, author_id, category_id)
       VALUES (:title, :content, :author_id, :category_id)');
     
     $query = $cnx->prepare($sql);
     $query->bindValue(':title', $postTitle, PDO::PARAM_STR);
     $query->bindValue(':content', $content, PDO::PARAM_STR);
     $query->bindValue(':author_id', $author, PDO::PARAM_INT);
     $query->bindValue(':category_id', $category, PDO::PARAM_INT);
     $query->execute();

     var_dump($query);
   
     header("location:admin.php");
     exit();
  }  

} 


// LORS DU CHARGEMENT DE LA PAGE SANS FORMULAIRE REMPLI**************************************  
 // / Récupération des auteurs
 $sql = (
  'SELECT 
      authors.firstname,
      authors.lastname
  FROM authors
  ORDER BY authors.lastname
  ' );

$query = $cnx->prepare($sql);
$query->execute();
$authors = $query->fetchAll();


// / Récupération des categories
 $sql = (
  'SELECT 
      categories.name
  FROM categories
  ORDER BY categories.name
  ' );

$query = $cnx->prepare($sql);
$query->execute();
$categories = $query->fetchAll();

  
  // Chargement de la page
    $administration = true;
    $title = "Administration creer un article";
    $template = './admin_create_article.phtml';

  include('../gabarit.phtml');






?>