<?php
require_once 'config.php';
//require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$checkPass = filter_input(INPUT_POST, 'checkPass', FILTER_SANITIZE_SPECIAL_CHARS);

if($password != $checkPass){
    $_SESSION['flash']= 'Senhas diferentes';
    header("Location: $base/signup.php");
    exit;
}

if($name && $email && $password){
    $message="";
    $user = new UserDaoMysql($pdo);
    if(!$user->findByEmail($email)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userCad = ["name"=>$name, "email"=>$email, "password"=>$hash];
        $id = $user->insert($userCad); 
        if($id > 0){
            $_SESSION['flash']= "Bem vindo $name, faça seu login";;
            header("Location: $base");
            exit;            
        } else {
            $message = "Erro no cadastro.";
        }    
    } else {
        $message = "Email já cadastrado";
    }
} else {
    $message = "Nome/Email/Senha em branco";
}
$_SESSION['flash']= $message;
header("Location: $base/signup.php");
exit;
?>