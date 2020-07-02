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
        $sql = $this->pdo->prepare("INSERT INTO userrelations 
        (user_from, user_to) VALUES (:user_from, :user_to)");
        $sql->bindValue(":user_from", $u->user_from);
        $sql->bindValue(":user_to", $u->user_to);
        $sql->execute();
    }

    public function delete(UserRelation $u){
        $sql = $this->pdo->prepare("DELETE FROM userrelations 
        WHERE user_from = :user_from AND user_to = :user_to");
        $sql->bindValue(":user_from", $u->user_from);
        $sql->bindValue(":user_to", $u->user_to);
        $sql->execute();
    }

    public function getFollowing($user_from){
        $users = [];
        $sql = $this->pdo->prepare('SELECT user_to FROM userrelations WHERE user_from = :user_from');
        $sql->bindValue(':user_from', $user_from);
        $sql->execute();
        if( $sql->rowCount() > 0 ){
            $userList = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach($userList as $user){
                $users[] = $user['user_to'];
            }
        }
        return $users;
    }

    public function getFollowers($user_to){
        $users = [];
        $sql = $this->pdo->prepare('SELECT user_from FROM userrelations WHERE user_to = :user_to');
        $sql->bindValue(':user_to', $user_to);
        $sql->execute();
        if( $sql->rowCount() > 0 ){
            $userList = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach($userList as $user){
                $users[] = $user['user_from'];
            }
        }
        return $users;
    }

    public function isFollowing($id_from, $id_to){
        $sql = $this->pdo->prepare("SELECT * FROM userrelations WHERE 
        user_from = :user_from AND user_to = :user_to");
        $sql->bindValue(":user_from", $id_from);
        $sql->bindValue(":user_to", $id_to);
        $sql->execute();
        if($sql->rowCount() > 0 ){
            return true;
        }
        return false;
    }
}

