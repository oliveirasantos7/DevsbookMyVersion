<?php

require_once 'models\Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';




class PostDaoMysql implements PostDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function insert(Post $p){
        $sql = $this->pdo->prepare("INSERT INTO posts(
            id_user,type,created_at, body)
      VALUES(:id_user,:type,:created_at,:body)");

   $sql->bindValue(':id_user', $p->id_user);
   $sql->bindValue(':type', $p->type);
   $sql->bindValue(':created_at', $p->created_at);
   $sql->bindValue(':body', $p->body);
   $sql->execute();

   return true;
    }

public function getHomeFeed($id_user){

//1-lista dos usuarios que eu sigo

$urDao = new UserRelationDaoMysql($this->pdo);
$userList =$urDao->getFollowing($id_user);
$userList[] = $id_user;

$array = [];

// // echo '<pre>';
// // print_r($userList);
// // echo '</pre>';
// exit;

//2-pegar os posts em ordem cronologica
$sql = $this->pdo->query("SELECT * FROM posts
WHERE id_user IN (".implode( ',', $userList).")
ORDER BY created_at DESC");

if($sql->rowCount() > 0){
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);


    //3-transformar os resultados em objetos
    $array = $this->_postListToObject($data, $id_user);

}

return $array;
    }


    public function getPhotosFrom($id_user){
        $array = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id_user = :id_user AND type = 'photo' ORDER BY created_at DESC");

        $sql->bindValue(':id_user', $id_user);
        $sql->execute();




        if($sql->rowCount() > 0){

            $data = $sql->fetchAll(PDO::FETCH_ASSOC);


                //3-transformar os resultados em objetos
                $array = $this->_postListToObject($data, $id_user);
        }



        return $array;


        
    }   


    private function _postListToObject($post_list, $id_user){

        $posts = [];
        $userDao = new UserDaoMysql($this->pdo);



        foreach($post_list as $post_item){
            $newPost = new Post();
            $newPost->id = $post_item['id'];
            $newPost->type = $post_item['type'];
            $newPost->created_at = $post_item['created_at'];
            $newPost->body = $post_item['body'];
            $newPost->mine = false;

            if($post_item['id_user'] == $id_user){
                $newPost->mine = true;
            }

            //pegar informações do usuario
            $newPost->user = $userDao->findById($post_item['id_user']);

            //informações sobre LIKE
            $newPost->likeCount = 0;
            $newPost->liked = false;

            //informações sobre COMMENTS
            $newPost->comments = [];

            $posts[] = $newPost;

        }


        return $posts;
    }



    //pega os posts do usuario
    public function getUserFeed($id_user){

        $array = [];
        
        // // echo '<pre>';
        // // print_r($userList);
        // // echo '</pre>';
        // exit;
        
        //-pegar os posts em ordem cronologica
        $sql = $this->pdo->prepare("SELECT * FROM posts
        WHERE id_user = :id_user
        ORDER BY created_at DESC");

        $sql->bindValue(':id_user', $id_user);
        $sql->execute();
        
        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        
            //3-transformar os resultados em objetos
            $array = $this->_postListToObject($data, $id_user);
        
        }
        
        return $array;
            }

   
   


}