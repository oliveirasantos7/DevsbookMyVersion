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
    
  
    //avatar
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
            $x = $avatarWidth - $newWidth;
            $y = $avatarHeight - $newHeight;
            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;


            $finalImage = imagecreatetruecolor($avatarWidth, $avatarHeight);

            switch($newAvatar['type']){
                case 'image/jpeg':
                case 'image/jpeg':
                $image = imagecreatefromjpeg($newAvatar['tmp_name']);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($newAvatar['tmp_name']);
                    break;

            }

            imagecopyresampled(
                $finalImage, $image,
                $x, $y, 0, 0,
                $newWidth, $newHeight, $widthOrig, $heightOrig
            );

            
            $avatarName = md5(time() . rand(0, 9999)) . '.jpg';


            imagejpeg($finalImage, './media/avatars/'.$avatarName, 100);


            $userInfo->avatar = $avatarName;

            // echo '<pre>';
            // print_r($_FILES);
            // echo '</pre>';
            // echo $newHeight.' X '.$newHeight;
            // exit;

        }
    }
    

    
    
    //cover
    if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
        $newCover = $_FILES['cover'];
    
        if (in_array($newCover['type'], ['image/jpg', 'image/png', 'image/jpeg'])) {
            $coverWidth = 850;
            $coverHeight = 313;
    
            list($widthOrig, $heightOrig) = getimagesize($newCover['tmp_name']);
            $ratio = $widthOrig / $heightOrig;
    
            $newWidth = $coverWidth;
            $newHeight = $newWidth / $ratio;
    
            if ($newHeight < $coverHeight) {
                $newHeight = $coverHeight;
                $newWidth = $newHeight * $ratio;
            }
            $x = $coverWidth - $newWidth;
            $y = $coverHeight - $newHeight;
            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;


            $finalImage = imagecreatetruecolor($coverWidth, $coverHeight);

            switch($newCover['type']){
                case 'image/jpeg':
                case 'image/jpeg':
                $image = imagecreatefromjpeg($newCover['tmp_name']);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($newCover['tmp_name']);
                    break;

            }

            imagecopyresampled(
                $finalImage, $image,
                $x, $y, 0, 0,
                $newWidth, $newHeight, $widthOrig, $heightOrig
            );

            
            $coverName = md5(time() . rand(0, 9999)) . '.jpg';


            imagejpeg($finalImage, './media/covers/'.$coverName, 100);


            $userInfo->cover = $coverName;

            // echo '<pre>';
            // print_r($_FILES);
            // echo '</pre>';
            // echo $newHeight.' X '.$newHeight;
            // exit;

        }
    }
    



    $userDao->update($userInfo);
}

header("Location: ".$base."/configuration.php");
exit;






