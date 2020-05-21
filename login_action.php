<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'models/User.php';

$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if($email && $password){
    $auth = new Auth($pdo, $base);
    if($auth->validateLogin($email,$password)){
        header("Location: $base");
        exit;
    }    
}
$_SESSION['flash']= 'Email/Senha inválidos';
header("Location: $base");
exit;
?>