<?php
require_once 'models/UserRelation.php';

class UserRelationDaoMysql implements UserRelationDao
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function insert(UserRelation $u){
        
    }

    public function getFollowing($id){
        $users = [];
        $sql = $this->pdo->prepare('SELECT user_to FROM userrelations WHERE user_from = :user_from');
        $sql->bindValue(':user_from', $id);
        $sql->execute();
        if( $sql->rowCount() > 0 ){
            $userList = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach($userList as $user){
                $users[] = $user['user_to'];
            }
        }
        return $users;
    }

    public function getFollowers($id){
        $users = [];
        $sql = $this->pdo->prepare('SELECT user_from FROM userrelations WHERE user_to = :user_to');
        $sql->bindValue(':user_to', $id);
        $sql->execute();
        if( $sql->rowCount() > 0 ){
            $userList = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach($userList as $user){
                $users[] = $user['user_from'];
            }
        }
        return $users;
    }
}

