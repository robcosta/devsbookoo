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

    

}