<?php


session_start();

session_destroy();
unset($_SESSION['email']);
unset($_SESSION['username']);

header('Location:../index.php');
