<?php
require_once 'dao/UserDaoMysql.php';
require_once 'User.php';
class Auth {
    private $pdo;
    private $base;
    private $dao;
    public function __construct($pdo, $base){
        $this->pdo = $pdo;
        $this->base = $base;
        $this->dao = new UserDaoMysql($this->pdo);
    }
    
    public function checkToken() {
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];
            $user = $this->dao->findbyToken($token);
            if($user){
                return $user;
            }
        }
        header("Location: $this->base/login.php");
        exit;
    }

    public function validateLogin($email,$password){
        $user = $this->dao->findByEmail($email);
        if($user) {
            if(password_verify($password, $user->password)){
                $token = md5(time().rand(0,9999));
                $_SESSION['token'] = $token;
                $user->token = $token;
                $this->dao->update($user);
                return true;
            }
        }        
        return false;
    }
    
    public function emailExists($email){
        return $this->dao->findByEmail($email) ? true : false;        
    }

    public function registerUser($name, $birthdate, $email, $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,9999));

        $newUser = new User();
        $newUser->name = ucwords($name);
        $newUser->birthdate = $birthdate;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->avatar = "avatar.jpg";
        $newUser->cover = "cover.jpg";
        $newUser->token = $token;

        $id = $this->dao->insert($newUser);

        $_SESSION['token'] = $token;
        
        return ($id > 0) ? true : false;
    }
}