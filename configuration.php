<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';


//chama a classe de autenticação
$auth = new Auth($pdo, $base);

//utiliza o metodo de usuario para redirecionar para login ou permitir o acesso ao sistema
$userInfo = $auth->checkToken();

//ativa o active
$activeMenu = 'config';

//instancia a database de post
$userDao = new UserDaoMysql($pdo);

require 'partials/header.php';
require 'partials/menu.php';
?>

<section class="feed mt-10">
<?php 
// echo '<pre>';
// print_r($userInfo);
// echo '</pre>';
// // exit;

?>

<style>
    .password-toggle {
        display: inline-block;
        cursor: pointer;
        margin-left: 10px;
    }
</style>


<h1>Configurações</h1>
<?php if(!empty($_SESSION['flash'])) :?>

<?=$_SESSION['flash'];?>
<?=$_SESSION['flash'] = '';?>

<?php endif;?>

<form method="post" enctype="multipart/form-data" class="config-form" action="configAction.php">

<label for="">
Novo avatar: <br>
<input type="file" name="avatar" id="">
</label>
<img class="mini" src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" />

<label for="">
Nova capa: <br>
<input type="file" name="cover" id="">
</label>
<img class="mini" src="<?=$base;?>/media/covers/<?=$userInfo->cover;?>" />
<hr>

<label for="">
Nome completo: <br>
<input type="text" name="name" id="" value="<?=$userInfo->name?>">
</label>

<label for="">
Email: <br>
<input type="email" name="email" id="" value="<?=$userInfo->email?>">
</label>

<label for="">
Data de nascimento: <br>
<input type="text" name="birthdate" id="birthdate" value="<?=
date('d/m/Y', strtotime($userInfo->birthdate));
?>">
</label>

<label for="">
Cidade: <br>
<input type="text" name="city" id="" value="<?=$userInfo->city?>">
</label>

<label for="">
Trabalho: <br>
<input type="text" name="work" id="" value="<?=$userInfo->work?>">
</label>
<hr>

<label for="password_confirmation">
    Nova senha: <br>
    <input type="password" name="password" id="password_confirmation">
    <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">Ver senha</span>
</label>

<br>

<label for="password">
    Confirmar nova senha: <br>
    <input type="password" name="password" id="password_confirmation">
    <span class="password-toggle" onclick="togglePasswordVisibility('password')">Ver senha</span>
</label>
<button class="button">Salvar</button>
</form>

</section>


<script src="https://unpkg.com/imask"></script>
    <script>

        IMask(
            document.getElementById("birthdate"),
            {mask:'00/00/0000'}
        )
    </script>


<?php
require 'partials/footer.php';
?>


<script>
    function togglePasswordVisibility(inputId) {
        var input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>