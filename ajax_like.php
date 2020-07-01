<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostLikeDaoMysql.php';

$userInfo = new Auth($pdo, $base);
$userInfo = $userInfo->checkToken();

$idPost = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

if(!empty($idPost)){
      echo $idPost;
      $postLikeDao = new PostLikeDaoMysql($pdo);
      $postLikeDao->likeToggle($idPost, $userInfo->id);
}
