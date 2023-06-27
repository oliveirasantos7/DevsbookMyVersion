<?php

require_once 'models/User.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/PostDaoMysql.php';




//metodo responsavel por criar uma consulta no banco de dados para verificação de um usuario
class UserDaoMysql implements UserDao{

private $pdo;

public function __construct(PDO $driver){
    $this->pdo = $driver;
}

private function generateUser($array, $full = false)
{
    $u = new User();
    $u->id = $array['id'] ?? 0;
    $u->email = $array['email'] ?? 0;
    $u->password = $array['password'] ?? 0;
    $u->name = $array['name'] ?? 0;
    $u->birthdate = $array['birthdate'] ?? 0;
    $u->city = $array['city'] ?? 0;
    $u->work = $array['work'] ?? 0;
    $u->avatar = $array['avatar'] ?? 0;
    $u->cover = $array['cover'] ?? 0;
    $u->token = $array['token'] ?? 0;

    if ($full) {
        $urDaoMysql = new UserRelationDaoMysql($this->pdo);

        $postDaoMysql = new PostDaoMysql($this->pdo);

        // follower = quem segue o usuário 
        $u->followers = $urDaoMysql->getFollowers($u->id);

        foreach($u->followers as $key => $follower_id){
            $newUser = $this->findById($follower_id);
            $u->followers[$key] = $newUser;
        }

        // following = quem o usuário segue
        $u->following = $urDaoMysql->getFollowing($u->id);

        foreach($u->following as $key => $follower_id){
            $newUser = $this->findById($follower_id);
            $u->following[$key] = $newUser;
        }


        // fotos
        $u->photos = $postDaoMysql->getPhotosFrom($u->id) ;
    }

    return $u;
}


//manda o token
//verifica o token no banco de dados
//se ele acha o token cria o objeto (generateUser)
//e retorna
//caso não encontre retorna false
public function findByToken($token){

    //cria a verificação de usuario no banco de dados
    if(!empty($token)){

        //faz a consulta pdo no sql
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");

        //prepara os campos
        $sql->bindValue(':token', $token);
        $sql->execute();

        //verifica e armazena caso receba os dados
        if($sql->rowCount() > 0){
            $data = $sql->fetch(PDO::FETCH_ASSOC); 

            //manda para o objeto os dados recebidos no banco 
            $user = $this->generateUser($data);
            return $user;
        }
    }
    
    return false;
}
    


public function findByEmail($email){

    //cria a verificação de usuario no banco de dados
    if(!empty($email)){

        //faz a consulta pdo no sql
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");

        //prepara os campos
        $sql->bindValue(':email', $email);
        $sql->execute();

        //verifica e armazena caso receba os dados
        if($sql->rowCount() > 0){
            $data = $sql->fetch(PDO::FETCH_ASSOC); 

            //manda para o objeto os dados recebidos no banco 
            $user = $this->generateUser($data);
            return $user;
        }
    }
    
    return false;
}


public function findById($id, $full = false){

      //cria a verificação de usuario no banco de dados
      if(!empty($id)){

        //faz a consulta pdo no sql
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");

        //prepara os campos
        $sql->bindValue(':id', $id);
        $sql->execute();

        //verifica e armazena caso receba os dados
        if($sql->rowCount() > 0){
            $data = $sql->fetch(PDO::FETCH_ASSOC); 

            //manda para o objeto os dados recebidos no banco 
            $user = $this->generateUser($data, $full);
            return $user;
        }
    }
    
    return false;

}

//pega pelo nome
public function findByName($name){

    $array = [];

     //cria a verificação de usuario no banco de dados
     if(!empty($name)){

        //faz a consulta pdo no sql
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE name LIKE :name");

        //prepara os campos
        $sql->bindValue(':name', '%'.$name.'%');
        $sql->execute();

        //verifica e armazena caso receba os dados
        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC); 

            foreach($data as $item){
                $array[] = $this->generateUser($item); 
            }   
        }
    }
    
    return $array;
}

//metodo responsavel por atualizar o usuario no banco de dados
public function update(User $u){
    
    $sql = $this->pdo->prepare("UPDATE users SET
    email = :email,
    password = :password, 
    name = :name, 
    birthdate = :birthdate, 
    city = :city, 
    work = :work, 
    avatar = :avatar, 
    cover = :cover, 
    token = :token 
    WHERE id = :id ");

    $sql->bindValue(':email', $u->email);
    $sql->bindValue(':password', $u->password);
    $sql->bindValue(':name', $u->name);
    $sql->bindValue(':birthdate', $u->birthdate);
    $sql->bindValue(':city', $u->city);
    $sql->bindValue(':work', $u->work);
    $sql->bindValue(':avatar', $u->avatar);
    $sql->bindValue(':cover', $u->cover);
    $sql->bindValue(':token', $u->token);
    $sql->bindValue(':id', $u->id);
    $sql->execute();

    return true;
}

//metodo responsavel
public function insert(User $u){
    
    $sql = $this->pdo->prepare("INSERT INTO users(
             email,password,name, birthdate,token)
       VALUES(:email,:password,:name,:birthdate,:token )");

    $sql->bindValue(':email', $u->email);
    $sql->bindValue(':password', $u->password);
    $sql->bindValue(':name', $u->name);
    $sql->bindValue(':birthdate', $u->birthdate);
    $sql->bindValue(':token', $u->token);
    $sql->execute();

    return true;
}







}