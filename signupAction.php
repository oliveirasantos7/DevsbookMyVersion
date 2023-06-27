<?php

require 'config.php';
require 'models/Auth.php';

$name = filter_input(INPUT_POST, 'name');

//formato 00/00/0000
$birthdate = filter_input(INPUT_POST, 'birthdate');

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

$password = filter_input(INPUT_POST, 'password');




//verifica se email e senha foram recebidos
if($email && $password && $birthdate && $name){

    //chama a classe de autenticação
$auth = new Auth($pdo, $base);

//verifica se o formato de data é correto
$birthdate = explode('/', $birthdate);
if(count($birthdate) !=3){
    
    $_SESSION['flash'] = 'Data de nascimento invalido';
    header("Location: ".$base."/signup.php");
    exit;
}

//verifica se a data enviada existe
$birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
if(strtotime($birthdate) === false){

    $_SESSION['flash'] = 'Data de nascimento invalido';
    header("Location: ".$base."/signup.php");
    exit; 
}

if($auth->emailExists($email) === false){

    $auth->registerUser($name, $email, $password, $birthdate);

    header("Location: ".$base);
    exit;

}else{
    $_SESSION['flash'] = 'E-mail já está em uso';
    header("Location: ".$base."/signup.php");
    exit; 
}


}

$_SESSION['flash'] = 'Campos não enviados';
header("Location: ".$base."/signup.php");
exit;
