<?php

require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';


//chama a classe de autenticação
$auth = new Auth($pdo, $base);

//utiliza o metodo de usuario para redirecionar para login ou permitir o acesso ao sistema
$userInfo = $auth->checkToken();

//ativa o active
$activeMenu = 'home';

//instancia a database de post
$postDao = new PostDaoMysql($pdo);

//chama nossa função de feed
$feed = $postDao->getHomeFeed($userInfo->id);



require 'partials/header.php';
require 'partials/menu.php';