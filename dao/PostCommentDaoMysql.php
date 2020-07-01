<?php
require_once 'models/PostComment.php';
require_once 'dao/UserDaoMysql.php';

class PostCommentDaoMysql implements PostCommentDAO {
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function getComment($id_post){
        $array = [];
        $sql= $this->pdo->prepare("SELECT * FROM postcomments WHERE id_post = :id_post");
        $sql->bindValue(":id_post", $id_post);
        $sql->execute();
        if($sql->rowCount() > 0 ){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach( $data as $item) {
                $userComment  = new UserDaoMysql($this->pdo);
                $newComment = new PostComment();
                $newComment->id = $item['id'];
                $newComment->id_post = $item['id_post'];
                $newComment->id_user = $item['id_user'];
                $newComment->created_at = $item['created_at'];
                $newComment->body = $item['body'];
                $newComment->user = $userComment->findById($item['id_user']);
                $array[] = $newComment;
            }
        }        
        return $array;
    }

    public function addComment(PostComment $pc){
        $sql = $this->pdo->prepare("INSERT INTO postcomments 
        (id_post, id_user, created_at, body) VALUES 
        (:id_post, :id_user, :created_at, :body)");
        $sql->bindValue(":id_post", $pc->id_post);
        $sql->bindValue(":id_user", $pc->id_user);
        $sql->bindValue(":created_at", $pc->created_at);
        $sql->bindValue(":body", $pc->body);
        $sql->execute();
        return $this->pdo->lastInsertId();
    } 
        

}