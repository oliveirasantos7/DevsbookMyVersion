<?php

class User{

    //metodos responsaveis pelo usuario
    public $id;
    public $email;
    public $password;
    public $name;
    public $birthdate;
    public $city;
    public $work;
    public $avatar;
    public $cover;
    public $token;
}

interface UserDao{
    public function findByToken($token);
    public function findByEmail($email);
    public function findById($id);
    public function update(User $u);
    public function insert(User $u);



    
}