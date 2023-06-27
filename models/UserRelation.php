<?php

class UserRelation{

    //metodos responsaveis pelo usuario
    public $id;
    public $userFrom;
    public $userTo;
    
}

interface UserRelationDao{
    public function insert(UserRelation $u);
    public function getFollowing($id);
    public function getFollowers($id);    

}