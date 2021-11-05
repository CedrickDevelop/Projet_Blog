<?php 

  session_start();
  
  require_once('./DBconnect.php');

  // Récupération des 10 derniers articles
  $sql = (
      'SELECT 
          posts.id,
          posts.title,
          posts.contents,
          -- SUBSTRING(posts.contents, 1, 150),
          -- posts.created_at,
          DATE_FORMAT(posts.created_at, "%W %e %M %Y") as created_at,
          categories.name,
          authors.firstname,
          authors.lastname
      FROM posts
      INNER JOIN  categories on posts.category_id = categories.id
      INNER JOIN  authors on posts.author_id = authors.id
      ORDER BY created_at DESC
      LIMIT 10
      ' );

  $query = $cnx->prepare($sql);
  $query->execute();
  $articles = $query->fetchAll();


    // foreach($_SERVER as $key => $value) {
    //   echo '$_SERVER['.$key.']='.$value .'<br />';
    //   }

    //   print_r($_COOKIE);    
    

  // Affichage de la page d'accueil
  $title = "Accueil";
  $template = './index.phtml';

  // Affichage du gabarit
  include('./gabarit.phtml');

?>