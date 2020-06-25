<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$userInfo = new Auth($pdo, $base);
$userInfo = $userInfo->checkToken();

$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_SPECIAL_CHARS);

if($body){
    $postDao = new PostDaoMysql($pdo);
    $post = new Post();
    $post->id_user = $userInfo->id;
    $post->type = 'text';
    $post->created_at = date("Y-m-d H:i:s");
    $post->body = $body;
    $postDao->insert($post);    
}

header('Location: '.$base);
exit;