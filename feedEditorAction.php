<?php
require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';


//chama a classe de autenticação
$auth = new Auth($pdo, $base);

//utiliza o metodo de usuario para redirecionar para login ou permitir o acesso ao sistema
$userInfo = $auth->checkToken();

//pega os dados do body
$body = filter_input(INPUT_POST, 'body');

//verifica se foi recebido um body
if($body){

    //pego a instancia de database
    $postDao = new PostDaoMysql($pdo);

    //crio um novo post
    $newPost = new Post();

    //dados do novo post
    $newPost->id_user = $userInfo->id;
    $newPost->type = 'text';
    $newPost->created_at = date('Y-m-d H:i:s');
    $newPost->body = $body;

    //insiro atraves do dao
    $postDao->insert($newPost);


}

//retorna para base
header("Location: ".$base);
exit;
