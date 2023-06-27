<?php

require 'config.php';
require 'models/Auth.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

$password = filter_input(INPUT_POST, 'password');


//verifica se email e senha foram recebidos
if($email && $password){

    //chama a classe de autenticação
$auth = new Auth($pdo, $base);

//verifica a classe de validação de login
if($auth->validateLogin($email, $password)){

    header("Location: ".$base);
    exit;

}


}

$_SESSION['flash'] = 'Email ou senha incorretos';
header("Location: ".$base."/login.php");
exit;
