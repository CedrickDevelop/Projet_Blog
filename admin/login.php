<?php

session_start();


  // Verification si l'utilisateur est deja connecté
  if (isset($_SESSION['email'])){
    header('Location:admin.php');
  }


  // Si le formulaire de connexion est rempli
  if (isset($_POST['submit_login'])) {
    // Connexion à la base de donnée
    require_once('../DBconnect.php');

    $email=$_POST['email_login'];
    $password=$_POST['password_login'];

    $errors=[];

    // Si l'email n'est pas valide
    if (empty($email) || (filter_var($email, FILTER_VALIDATE_EMAIL) == false)  ){
      $errors['email'] = "Veuillez entrer un email valide";
      goto show_template;
    }

    // Si le mot de passe n'est pas valide
    if ((empty($password)) || (strlen($password) < 4 )) {
      $errors['password'] = "Veuillez entrer un mot de passe valide supérieur à 4 caractères" ;
      goto show_template;
    }

    // Appel à la base de données pour vérifier le compte
    $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
        $query = $cnx->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch();

        if ($user) {
            if ($password === $user['password']) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                header('Location: ./admin.php');
                exit();
            } else {
                $errors['formulaire'] = "Le mot de passe ou identifiant sont invalides";
            }
        } else {
          $errors['formulaire'] = "Le mot de passe ou identifiant sont invalides";
        }

  }


  show_template :

  // Chargement de la page
  $administration = true;
  $title = "Login";
  $template = './login.phtml';

  include('../gabarit.phtml');

?>