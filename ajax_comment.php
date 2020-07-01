<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostCommentDaoMysql.php';

$userInfo = new Auth($pdo, $base);
$userInfo = $userInfo->checkToken();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$txt = filter_input(INPUT_POST, 'txt', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$array = [];

if($id && $txt){
      $newComment = new PostComment();
      $postCommentDao = new PostCommentDaoMysql($pdo);
      $newComment->id_post = $id;
      $newComment->id_user = $userInfo->id;
      $newComment->created_at = DATE('Y-m-d H:i:s');
      $newComment->body = $txt;
      $postCommentDao->addComment($newComment);
      $array = [
            'error' => '',
            'link' => $base.'/perfil.php?id='.$userInfo->id,
            'avatar' => $base.'/media/avatars/'.$userInfo->avatar,
            'name' => $userInfo->name,
            'body' => $txt
      ];
}

header("Content-Tpe: application/json");
echo json_encode($array);
exit;