<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'config';
$firtName = current(explode(" ", $userInfo->name));

require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed mt-10">
    <h1>Configurações</h1>
    <form  method="POST" action="<?=$base; ?>/configuracoes_action.php" enctype="multipart/form-data" class="config-form">
        <?php if(!empty($_SESSION['flash'])): ?>
            <div class="flash"><?=$_SESSION['flash'];?></div>
        <?php $_SESSION['flash']=""; endif;?>
        
        <label>
            Novo Avatar:<br/>
            <input type="file" name="avatar"/><br/>
            <img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" class="mini" alt="">
        </label>
        <label>
            Nova Capa:<br/>
            <input type="file" name="cover"/><br/>
            <img src="<?=$base;?>/media/covers/<?=$userInfo->cover;?>" class="mini" alt="">
        </label>

        <hr/>
        <input type="hidden" name="id" value="<?=$userInfo->id;?>"/>
        <label>
            Nome Completo*:<br/>
            <input type="text" name="name" value="<?=$userInfo->name;?>"/>
        </label>
        <label>
            E-mail*:<br/>
            <input type="email" name="email" value="<?=$userInfo->email;?>"/>
        </label>
        <label>
            Data de Nascimento*:<br/>
            <input type="text" name="birthdate"  id="birthdate" value="<?=date('d/m/Y', strtotime($userInfo->birthdate));?>"/>
        </label>
        <label>
            Cidade:<br/>
            <input type="text" name="city" value="<?=$userInfo->city;?>"/>
        </label>
        <label>
            Trabalho:<br/>
            <input type="text" name="work" value="<?=$userInfo->work;?>"/>
        </label>
        <hr/>
        <label>
            Nova Senha:<br/>
            <input type="password" name="password"/>
        </label>
        <label>
            Confirmar Nova Senha:<br/>
            <input type="password" name="checkPass"/>
        </label>
        
        <button class="button">Salvar</button>
        
    </form>            
</section>
<script src="https://unpkg.com/imask"></script>
    <script>
         setTimeout(()=>{
            document.querySelector('.flash').classList.add("flash-hide");
        },2000);
        
        IMask(
            document.getElementById("birthdate"),
            {mask:'00/00/0000'}
        );
</script>

<?php require 'partials/footer.php';?>