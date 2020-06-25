<?php
class Post {
    public $id;
    public $id_user;
    public $type;// text/photo
    public $created_at;
    public $body;
}

interface PostDao {
    public function insert(Post $p);
    public function getHomeFeed($id_user);
    public function getUserFeed($id_user);
    public function getPhotosFrom($id_user);
}