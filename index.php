<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';


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

// echo '<pre>';
// print_r($feed);
// echo '</pre>';
// exit;



require 'partials/header.php';
require 'partials/menu.php';


//require 'app.php';


?>

<section class="feed mt-10">
<div class="row">

<div class="column pr-5">

<?php require 'partials/feedEditor.php';?>

<?php foreach($feed as $item):?>

  <?php require 'partials/feedItem.php';?>

<?php endforeach;?>


    
</div>




<div class="column side pl-5">
      <div class="box banners">
        <div class="box-header">
         <div class="box-header-text">Patrocinios</div>
         <div class="box-header-buttons">
                                
         </div>
         </div>
            <div class="box-body">
            <a href=""><img src="https://alunos.b7web.com.br/media/courses/php-nivel-1.jpg" /></a>
         <a href=""><img src="https://alunos.b7web.com.br/media/courses/laravel-nivel-1.jpg" /></a>
        </div>
          </div>
        <div class="box">
        <div class="box-body m-10">
        Criado com ❤️ por B7Web
        </div>
        </div>
</div>

</div>
</section>


<?php
require 'partials/footer.php';
?>

