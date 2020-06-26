<?php
require_once 'config.php';
require_once 'models/Auth.php';

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$checkPass = filter_input(INPUT_POST, 'checkPass', FILTER_SANITIZE_SPECIAL_CHARS);

if ($password != $checkPass) {
    message('Senhas diferentes');
}

if ($name && $email && $birthdate && $password) {
    //Verificação da data de nascimento
    $birthdate = explode("/", $birthdate);
    if(count($birthdate) != 3) {
        message("Data de nascimento incompleta.");
    } elseif(!checkdate($birthdate[1],$birthdate[0],$birthdate[2])) {
        message("Data de nascimento inválida!");
    } else {
        $birthdate = "$birthdate[2]-$birthdate[1]-$birthdate[0]";
        if(strtotime() < strtotime($birthdate)){
            message("Data de nascimento maior que data atual.");
        }    
        $auth = new Auth($pdo, $base);
        if (!$auth->emailExists($email)){            
            if ($auth->registerUser($name, $birthdate, $email, $password)){           
                header("Location: $base");
                exit;
            } else {
                message("Erro no cadastro.");
            }
        } else {
            message("Email já cadastrado");
        }
    }
} else {
    message("Preencha todos os campos.");
}

function message($msg){
    global $base;
    $_SESSION['flash'] = $msg;
    header("Location: $base/signup.php");
    exit;
}
