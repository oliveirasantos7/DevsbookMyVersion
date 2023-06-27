<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';
//require 'dao/UserDaoMysql.php';



//chama a classe de autenticação
$auth = new Auth($pdo, $base);

//utiliza o metodo de usuario para redirecionar para login ou permitir o acesso ao sistema
$userInfo = $auth->checkToken();

//ativa o active
$activeMenu = 'photos';
$user = [];
$feed = [];

//pega o id do perfil
$id = filter_input(INPUT_GET, 'id');

if(!$id){
    $id = $userInfo->id;
}

if($id != $userInfo->id){
    $activeMenu = '';

}

$postDao = new PostDaoMysql($pdo);
$userDao = new UserDaoMysql($pdo);

// echo "ID: ".$id;
// exit;

//pegar informações do usuario
$user = $userDao->findById($id, true);
if(!$user){
    header("Location: ".$base);
    exit;
}

// $dateFrom = new DateTime($user->birthdate);
// $dateTo = new DateTime('today');
// $user->ageYears = $dateFrom->diff($dateTo)->y;



//verificar se eu sigo o usuario


// //instancia a database de post
// $postDao = new PostDaoMysql($pdo);

// //chama nossa função de feed
// $feed = $postDao->getHomeFeed($userInfo->id);

// // echo '<pre>';
// // print_r($feed);
// // echo '</pre>';
// // exit;



require 'partials/header.php';
require 'partials/menu.php';


//require 'app.php';


?>
 <section class="feed">

<div class="row">
    <div class="box flex-1 border-top-flat">
        <div class="box-body">
            <div class="profile-cover" style="background-image: url('<?=$base?>/media/covers/<?=$user->cover?>');"></div>
            <div class="profile-info m-20 row">
                <div class="profile-info-avatar">
                    <img src="<?=$base?>/media/avatars/<?=$user->avatar?>" />
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-name-text"><?=$user->name?></div>


                    <?php if(!empty ($user->city)):?>

                    <div class="profile-info-location"><?=$user->city?></div>

                    <?php endif;?>


                </div>
                <div class="profile-info-data row">
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->followers);?></div>
                        <div class="profile-info-item-s">Seguidores</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->following);?></div>
                        <div class="profile-info-item-s">Seguindo</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->photos);?></div>
                        <div class="profile-info-item-s">Fotos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

<div class="column">
                                
    <div class="box">
        <div class="box-body">

            <div class="full-user-photos">

        <?php  foreach($user->photos as $key=> $item): ;?>
            <div class="user-photo-item">
                    <a href="#modal-<?=$key;?>" rel="modal:open">
                        <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                    </a>
                    <div id="modal-<?=$key;?>" style="display:none">
                        <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                    </div>
                </div>
            <?php endforeach;?>

            <?php if(count($user->photos)=== 0):?>

                Não há fotos deste usuario.

            <?php endif;?>



        
                </div>
            </div>
        </div>
    </div>
                            
</div>

</section>

<script type="text/javascript" src="assets/js/script.js"></script>
    <script type="text/javascript" src="assets/js/vanillaModal.js"></script>

<?php
require 'partials/footer.php';
?>



    