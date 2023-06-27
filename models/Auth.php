<?php
//chama o arquivo onde se encontra nossa classe
require_once 'dao/UserDaoMysql.php';


class Auth{
    private $pdo;
    private $base;
    private $dao;


    public function __construct(PDO $pdo, $base){
        
        $this->pdo = $pdo;
        $this->base = $base;
        $this->dao  = new UserDaoMysql($this->pdo);

    }

    //metodo responsavel por redirecionar o usuario com base no token
    //verifica se tem uma sessão com nome token
    //pega esse dado e manda para UserDaoMysql
    //verificando no banco de dados se existe um token associado a esse usuario
    //se existir ele manda para usuario permitindo que esse faça o login 
    //caso não exista ou não tenha uma assosiação redireciona para a pagina de login
    public function checkToken(){
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];

            //chama nossa classe de database
           // $userDao = new UserDaoMysql($this->pdo);

            //cria um usuario com base no token
            $user = $this->dao->findByToken($token);

            //retorna o usuario caso encontre
            if($user){
                return $user;
            }
        }
        //redireciona para login caso não acossie o usuario com o token
        header("Location: ".$this->base."/login.php");
        exit;
    }


    //verifica o email
  public function validateLogin($email, $password){
   
    //$userDao = new UserDaoMysql($this->pdo);

    $user = $this->dao->findByEmail($email);

    //se achar o email
    if($user){

        //varifica a senha
        if(password_verify($password, $user->password)){

            //cria um  token
            $token = md5(time().rand(0, 9999));

            //coloca o token na sessão
            $_SESSION['token'] = $token;
            
            //coloca o token no usuario
            $user->token = $token;

            //atualiza no banco de dados
            $this->dao->update($user);

            //retorna sucesso
            return true;
        }
    }

    return false;
  }


  //verifica se o email já esta em uso
  public function emailExists($email){
   // $userDao = new UserDaoMysql($this->pdo);

    return $this->dao->findByEmail($email) ? true : false;
  }


  //metodo responsavel por cadastrar um usuario
  public function registerUser($name, $email, $password, $birthdate){

    //chama a instancia de database
    //$userDao = new UserDaoMysql($this->pdo);

    //metodo de novo usuario
    $newUser = new User();

    //cria um hash de senha
    $hash = password_hash($password, PASSWORD_DEFAULT);

     //cria um  token
     $token = md5(time().rand(0, 9999));


     //preenche o novo usuario
    $newUser->name = $name;
    $newUser->email = $email;
    $newUser->password = $hash;
    $newUser->birthdate = $birthdate;
    $newUser->token = $token;

    //coloca o token na sessão
    $_SESSION['token'] = $token;
    
    //chama o metodo de inserção no database
    $this->dao->insert($newUser);
  }
}