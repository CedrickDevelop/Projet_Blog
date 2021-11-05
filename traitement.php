<?php 



// traitement des commentaires vides

if ($_POST['comment_pseudo'] == ''){
  $message_comment_error = "Veuillez remplir votre pseudo" ;
  header("location:show_article.php?id=".$_POST['comment_id_posts'].'&error=0');
  exit();

} 
if ($_POST['comment_content'] == ''){
  $message_comment_error = "Veuillez remplir votre message" ;
  header("location:show_article.php?id=".$_POST['comment_id_posts'].'&error=0');
  exit();
} 

require_once('./DBconnect.php');
  // traitement des commentaires sur la page show article
  $pseudo = filter_var($_POST['comment_pseudo'], FILTER_SANITIZE_STRING );
  $content = filter_var($_POST['comment_content'], FILTER_SANITIZE_STRING );
  $id = filter_var($_POST['comment_id_posts'], FILTER_SANITIZE_NUMBER_INT );

  $sql = (
    'INSERT INTO 
        comments ( nickname, content, post_id) 
     VALUES 
        (:pseudo, :content, :id)   
    ' );
  
  $query = $cnx->prepare($sql);
  $query->bindValue(':pseudo', $pseudo);
  $query->bindValue(':content', $content);
  $query->bindValue(':id', $id);
  $query->execute();
  $comments = $query->fetchAll();

  $message_comment = "dont le votre, merci ".$pseudo." de votre participation";

  header("location:show_article.php?id=".$_POST['comment_id_posts'].'&error=1');
  exit();




?>