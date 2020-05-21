<?php
require_once 'dao/UserDaoMysql.php';
class Auth {
    private $pdo;
    private $base;
    public function __construct($pdo, $base){
        $this->pdo = $pdo;
        $this->base = $base;
    }
    
    public function checkToken() {
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];
            $userDao = new UserDaoMysql($this->pdo);
            $user = $userDao->findbyToken($token);
            if($user){
                return $user;
            }
        }
        header("Location: $this->base/login.php");
        exit;
    }

    public function validateLogin($email,$password){
        $userDao = new UserDaoMysql($this->pdo);
        $user = $userDao->findByEmail($email);
        if($user) {
            if(password_verify($password, $user->password)){
                $token = md5(time().rand(0,9999));
                $_SESSION['token'] = $token;
                $user->token = $token;
                $userDao->update($user);
                return true;
            }
        }        
        return false;
    }
    
}