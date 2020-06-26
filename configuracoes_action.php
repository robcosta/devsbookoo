<?php
require_once 'config.php';
require_once 'models/Auth.php';
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$userDao = new UserDaoMysql($pdo);

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_SPECIAL_CHARS);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
$work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$checkPass = filter_input(INPUT_POST, 'checkPass', FILTER_SANITIZE_SPECIAL_CHARS);

//PASSWORD
if($password){
    if ($password != $checkPass) {
       message('Nova senha e confirmar nova senha diferentes.');
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $userInfo->password = $hash;                                                                                                   
}

if ($name && $email && $birthdate) {
    $userInfo->name = ucwords($name);  
    $userInfo->city = ucwords($city);
    $userInfo->work = ucwords($work);
    //EMAIL
    if($userInfo->email != $email){
        if($userDao->findByEmail($email)){
            message('Email já cadastrado');
        }
        $userInfo->email = $email;        
    }
    //BIRTHDATE    
    $userBirthdate = explode("-", $userInfo->birthdate);
    $userBirthdate = "$userBirthdate[2]/$userBirthdate[1]/$userBirthdate[0]";
    if($userBirthdate != $birthdate){
        $birthdate = explode("/", $birthdate);
        if(count($birthdate) != 3) {
            message("Data de nascimento incompleta.");
        } elseif(!checkdate($birthdate[1],$birthdate[0],$birthdate[2])) {
            message("Data de nascimento inválida!");
        } else {
            $birthdate = "$birthdate[2]-$birthdate[1]-$birthdate[0]";
            if(strtotime() < strtotime($birthdate)){
                message("Data de nascimento não pode ser maior que a data atual.");
            }
            $userInfo->birthdate = $birthdate;
        }
    }

    //AVATAR
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])){        
        $userInfo->avatar = resizeImage($_FILES['avatar'],200,200,"avatars");
    }
    //COVER
    if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])){        
        $userInfo->cover = resizeImage($_FILES['cover'],850,313,"covers");
    }
 
    $userDao->update($userInfo);
    message("Alteração realizada com sucesso!");
    
} else {
    message("Nome ou Email o Data de Nascimento não preenchidos!");
}


function message($msg){
    global $base;
    $_SESSION['flash'] = $msg;
    header("Location: $base/configuracoes.php");
    exit;
}

function resizeImage($newImage, $width=200, $height=200, $pasta){
    global $base;
    $imageName="avatar.jpg";
    if(in_array($newImage['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
        $imageWidth = $width;
        $imageHeight = $height;

        list($widthOrig, $heighOrig) = getimagesize($newImage['tmp_name']);
        $ratio = $widthOrig / $heighOrig;

        $newWidth = $imageWidth;
        $newHeight = $newWidth / $ratio;

        if($newHeight < $imageHeight) {
            $newHeight = $imageHeight;
            $newWidth = $newHeight * $ratio;
        }

        $x = $imageWidth - $newWidth;
        $y = $imageHeight - $newHeight;
        $x = $x > 0 ? $x : $x/2;
        $y = $y > 0 ? $y : $y/2;

        $finalImage = imagecreatetruecolor($imageWidth, $imageHeight);
        switch($newImage['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($newImage['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($newImage['tmp_name']);
                break;
        }
         
        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heighOrig            
        ); 
    
        $imageName = md5(time().rand(0,9999)).'.jpg';
        imagejpeg($finalImage,"./media/$pasta/$imageName");
    }
    return $imageName;
}
