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

$name = filter_input(INPUT_POST,'name');
$email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST,'birthdate');
$city = filter_input(INPUT_POST,'city');
$work = filter_input(INPUT_POST,'work');
$password = filter_input(INPUT_POST,'password');
$password_confirmation = filter_input(INPUT_POST,'password_confirmation');

if($name && $email){

    $userInfo->name = $name;
    $userInfo->city = $city;
    $userInfo->work = $work;

    //email
    if($userInfo->email != $email){
        if($userDao->findByEmail($email) === false){

            $userInfo->email = $email;
            $_SESSION['flash'] = 'Email alterado com sucesso!';
            header("Location: ".$base."/configuration.php");
            exit; 

        }else{
            $_SESSION['flash'] = 'O email inserido ja esta em uso';
            header("Location: ".$base."/configuration.php");
            exit;


            $_SESSION['flash'] = '';
        }
    }

 //birthdate   
//verifica se o formato de data é correto
$birthdate = explode('/', $birthdate);
if(count($birthdate) !=3){
    
    $_SESSION['flash'] = 'Data de nascimento invalido';
    header("Location: ".$base."/configuration.php");
    exit;
}

//verifica se a data enviada existe
$birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
if(strtotime($birthdate) === false){

    $_SESSION['flash'] = 'Data de nascimento invalido';
    header("Location: ".$base."/configuration.php");
    exit; 
}

    $userInfo->birthdate = $birthdate;


    if (!empty($password)) {
        if ($password === $password_confirmation) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userInfo->password = $hash;
            $_SESSION['flash'] = 'Senha alterada com sucesso';
            header("Location: ".$base."/configuration.php");
            exit; 
        } else {
            $_SESSION['flash'] = 'As senhas não conferem';
            header("Location: ".$base."/configuration.php");
            exit; 
        }
    }
    
  
    
    if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
        $newAvatar = $_FILES['avatar'];
    
        if (in_array($newAvatar['type'], ['image/jpg', 'image/png', 'image/jpeg'])) {
            $avatarWidth = 200;
            $avatarHeight = 200;
    
            list($widthOrig, $heightOrig) = getimagesize($newAvatar['tmp_name']);
            $ratio = $widthOrig / $heightOrig;
    
            $newWidth = $avatarWidth;
            $newHeight = $newWidth / $ratio;
    
            if ($newHeight < $avatarHeight) {
                $newHeight = $avatarHeight;
                $newWidth = $newHeight * $ratio;
            }
            
            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';
            
            echo $newHeight.' X '.$newHeight;
        
            exit;

        }
    }
    
    


    $userDao->update($userInfo);
}

header("Location: ".$base."/configuration.php");
exit;






