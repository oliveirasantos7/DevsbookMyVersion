<?php

require_once 'models/UserRelation.php';


class UserRelationDaoMysql implements UserRelationDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
        
    }

    public function insert(UserRelation $u){

    }


    //pega a lista de usuarios que o id segue
    public function getFollowing($id){
        $users = [];

       

        //seleciona na tabela de user_to 
        $sql = $this->pdo->prepare("SELECT user_to FROM usersrelations WHERE user_from = :user_from");

            $sql->bindValue(':user_from', $id);
            $sql->execute();




        if($sql->rowCount() > 0){
            $data = $sql->fetchAll();
            foreach($data as $item){
                $users[] = $item['user_to'];
            }
        }

        return $users;
        
    }

    //pega a lista de usuarios que são seguidos pelo usuario
    public function getFollowers($id){
        

        $users = [];


        //seleciona na tabela de user_to 
        $sql = $this->pdo->prepare("SELECT user_from FROM usersrelations WHERE user_to = :user_to");

            $sql->bindValue(':user_to', $id);
            $sql->execute();




        if($sql->rowCount() > 0){
            $data = $sql->fetchAll();
            foreach($data as $item){
                $users[] = $item['user_from'];
            }
        }

        return $users;
        
    }
}