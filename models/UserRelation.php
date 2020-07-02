<?php
class UserRelation {
    public $id;
    public $user_from;
    public $user_to; 
   
}

interface UserRelationDao {
    public function insert(UserRelation $u);
    public function delete(UserRelation $u);
    public function getFollowing($user_from);
    public function getFollowers($user_to);
    public function isFollowing($id_from, $id_to);
}