<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';

class PostDaoMysql implements PostDao
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function insert(Post $p)
    {
        $sql = $this->pdo->prepare("INSERT INTO posts
        (id_user, type, created_at, body)
        VALUES (:id_user, :type, :created_at, :body)");
        $sql->bindValue(":id_user", $p->id_user);
        $sql->bindValue(":type", $p->type);
        $sql->bindValue(":created_at", $p->created_at);
        $sql->bindValue(":body", $p->body);
        $sql->execute();
        return $this->pdo->lastInsertId();
    }

    //Composição do feed do usuário
    public function getHomeFeed($id_user)
    {    
        $array = [];   
        // 1. Lista dos usários que EU sigo;
        $urDao = new UserRelationDaoMysql($this->pdo);
        $userList = $urDao->getFollowing($id_user);
        $userList[] = $id_user;
    
        // 2. Pegar meus posts e os dos usuários do ítem 1 ordenados pela data
        $sql = $this->pdo->query("SELECT * FROM posts WHERE id_user IN (".implode(',', $userList).") ORDER BY created_at DESC");
        $sql->execute();
        if( $sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            // 3. Transformar o resultado em objetos para serem exibidos
            $array = $this->_postListToObject($data, $id_user);
        }       
        return $array;
    }

    //Composição do feed apenas do usuário
    public function getUserFeed($id_user)
    {    $array = [];
        // 1. Pegar meus posts ordenados pela data
        $sql = $this->pdo->prepare("SELECT * FROM posts 
        WHERE id_user = :id_user 
        ORDER BY created_at DESC");
        $sql->bindValue(":id_user", $id_user);
        $sql->execute();
        if( $sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            // 2. Transformar o resultado em objetos para serem exibidos
            $array = $this->_postListToObject($data, $id_user);
        }       
        return $array;
    }


    public function getPhotosFrom($id_user){
        $array = [];
        // 1. Pegar meus posts do tipó photo ordenados pela data
        $sql = $this->pdo->prepare("SELECT * FROM posts 
        WHERE id_user = :id_user AND type = 'photo' 
        ORDER BY created_at DESC");
        $sql->bindValue(":id_user", $id_user);       
        $sql->execute();
        if( $sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            // 2. Transformar o resultado em objetos para serem exibidos
            $array = $this->_postListToObject($data, $id_user);
        }       
        return $array;
    }

    private function _postListToObject($post_list, $id_user){
        $userDao = new UserDaoMysql($this->pdo);
        $posts = [];
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
            // Infromações sobre USUÁRIOS
            $newPost->user = $userDao->findById($post_item['id_user']);

            // Informações sobre LIKE
            $newPost->likeCount = 0;
            $newPost->liked = false;

            // Informações sobre COMMENTS
            $newPost->comments = [];

            $posts[] = $newPost;
        }        
        return $posts;
    }


    
}
