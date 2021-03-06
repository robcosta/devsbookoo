<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$userInfo = new Auth($pdo, $base);
$userInfo = $userInfo->checkToken();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id){
    $postDao = new PostDaoMysql($pdo);    
    $postDao->delete($id, $userInfo->id);   
}

header('Location: '.$base);
exit;