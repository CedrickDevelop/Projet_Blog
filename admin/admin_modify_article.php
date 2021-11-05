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
// $id;  



// // *********************************************************************
// // VERIFICATION DE PAGE ************************************************
// // *********************************************************************
// if ( isset($_POST['submit']) 
//   && ((empty($_POST['write_title'])) 
//   || (empty($_POST['write_content']))) ) {

//   $messageFormulaire = "Veuillez remplir tous les champs du formulaire";
// } 

// else if  ( isset($_POST['submit']) 
//         && (preg_match("^[A-Za-z '-]+$",$_POST['write_title']))
//         && ((strlen($_POST['write_title']) > 100) 
//         ||  (strlen($_POST['write_title']) < 10)) 
//         ) {
//   $message_write_title = "Votre titre doit contenir entre 10 et 100 caractères normaux";
//   $messageFormulaire = "On est au bloc 2";
// }

// else if  ( isset($_POST['submit']) 
//           && (preg_match("^[A-Za-z '-]+$",$_POST['write_title']))
//           && ((strlen($_POST['write_content']) > 1000) 
//           || (strlen($_POST['write_content']) < 100)) 
//           ) {
//   $message_write_content = "Votre texte doit contenir entre 100 et 1000 caractères normaux";
//   $messageFormulaire = "On est au bloc 3";
// }

// //RECUPERATION DES IDS AUTEUR ET CATEGORIE***************************
//  $author = preg_split("/[\s,]+/",$_POST['select_author']);
//  $author_firstname = $author[0];
//  $author_lastname = $author[1];

//  $category = $_POST['select_category'];

//  $id = $_POST['id'];

//  // SELECT Categories
//  $sql = ("SELECT id FROM categories WHERE name= '{$category}' " );
//   $query = $cnx->prepare($sql);
//   $query->execute();
//   $category = $query->fetch();
//   $category = implode($category);
//   // SELECT Author
//   $sql = ("SELECT id FROM authors WHERE firstname = '{$author_firstname}' AND lastname = '{$author_lastname}' " );
//   $query = $cnx->prepare($sql);
//   $query->execute();
//   $author = $query->fetch();
//   $author = implode($author);

// // // NETTOYAGE DES DONNEES***************************
//   $title = filter_var($_POST['write_title'], FILTER_SANITIZE_STRING );
//   $title = trim($title);
//   $title = htmlspecialchars($title);
//   $title = stripslashes($title);

//   $content = filter_var($_POST['write_content'], FILTER_SANITIZE_STRING );
//   $content = trim($content);
//   $content = htmlspecialchars($content);
//   $content = stripslashes($content);


// Initialisation de l'ID
if (!empty($_GET['id'])){
  $id = $_GET['id'];
} else {
  $id = $_POST['submit'];
}

$postTitle = '';
$content = '';
$category_id = '';
$author_id = '';
  // VERIFICATIONS DE BASE SELON CORRECTION PROF***************************
//Si TOUT EST OK ON ENVOI EN BDD **************************************  
if ( (isset($_POST['submit']) && (!empty($_POST))) ) {

  // NETTOYAGE DES DONNEES***************************
  function checkData($val){
    $val = trim($val);
    $val = stripslashes($val);
    $val = htmlspecialchars($val);
    $val = filter_var($val, FILTER_SANITIZE_STRING );
    return $val;
  }

  function checkRegex($data){
    preg_match("^[A-Za-z '-]+$^",$data)? true : false ;
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
  // if (!checkRegex($postTitle)) {
  //     $error['postTitle'] = 'Vérifiez les infromations inscrites dans le titre';
  // }
  if (empty($content)) {
      $error['content'] = 'Le contenu est obligatoire';
  } 
  // if (!checkRegex($content)) {
  //     $error['content'] = 'Vérifiez les informations inscrites dans l\'article';
  // } 
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
    $author = implode($author);
  } else {
    $error['author'] = 'Vérifier le nom de l\'auteur.';
  }


    // VERIFICATIONS DE CATEGORIE***************************
  if (ctype_alpha($category)){
    $sql = ("SELECT id FROM categories WHERE name= '{$category}' " );
    $query = $cnx->prepare($sql);
    $query->execute();
    $category = $query->fetch();
    $category = implode($category);

  } else {
    $error['category'] = 'Vérifier le nom de la categorie.';
  }  

// // MISE A JOUR DES INFORMATIONS EN BDD***************************
  if (empty($error)) {  
    $sql = (
      " UPDATE  posts 
        SET  title = '{$postTitle}', 
            contents = '{$content}',
            author_id = '{$author}',
            category_id = '{$category}'
        WHERE id = '{$id}'
    ");

    $query = $cnx->prepare($sql);
    $query->execute();

    header("location:admin.php");
    exit();
  } 

}


// *********************************************************************
// CHARGEMENT DE PAGE RECUPERATION DES INFORMATIONS DE L'ARTICLE *******
// *********************************************************************
  $sql = (
    "SELECT 
        posts.id,
        categories.name,
        authors.firstname,
        authors.lastname,
        posts.title,
        posts.contents,
        posts.created_at
    FROM posts
    INNER JOIN  categories on posts.category_id = categories.id
    INNER JOIN  authors on posts.author_id = authors.id
    WHERE posts.id='{$id}'
    " );
  $query = $cnx->prepare($sql);
  $query->execute();
  $article = $query->fetch(); 



 // / Récupération des auteurs
 $sql = (
  'SELECT 
      authors.firstname,
      authors.lastname,
      authors.id
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
    $template = './admin_modify_article.phtml';

  include('../gabarit.phtml');

 




?>