<?php
require_once 'models/PostLike.php';

class PostLikeDaoMysql implements PostLikeDAO {
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function getLikeCount($id_post){
        $sql = $this->pdo->prepare("SELECT COUNT(*) AS c FROM postlikes WHERE id_post = :id_post");
        $sql->bindValue(":id_post", $id_post);
        $sql->execute();
        $data = $sql->fetch();
        return $data['c'];
    }

    public function isLiked($id_post, $id_user){
        $data = false;
        $sql = $this->pdo->prepare("SELECT * FROM postlikes WHERE (id_post = :id_post AND id_user = :id_user)");
        $sql->bindValue(":id_post", $id_post);
        $sql->bindValue(":id_user", $id_user);
        $sql->execute();
        if($sql->rowCount() > 0){
            $data = true;
        }
        return $data;
    }

    public function likeToggle($id_post, $id_user){
        $date = date('Y-m-d H:i:s');
        if($this->isLiked($id_post, $id_user)){
            $sql = "DELETE FROM postlikes WHERE 
                id_post = :id_post AND 
                id_user = :id_user";
        } else {      
            $sql = "INSERT INTO postlikes 
                (id_post, id_user, created_at) VALUES 
                (:id_post, :id_user, NOW())";
        }
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue("id_post", $id_post);
        $sql->bindValue("id_user", $id_user);
        $sql->execute();        
    }

}