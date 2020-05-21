<?php
require 'config.php'

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cadastro</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />    
</head>
<body>
    <header>
        <div class="container">
            <a href="<?=$base;?>"><img src="<?=$base;?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        <form method="POST" action="<?=$base;?>/signu_action.php">
            <?php if(!empty($_SESSION['flash'])): ?>
                <div class="flash"><?=$_SESSION['flash'];?></div>
            <?php $_SESSION['flash']=""; endif;?>
            <input placeholder="Digite seu nome completo" class="input" type="text" name="name" />
            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />
            <input placeholder="Digite sua senha" class="input" type="password" name="password" />
            <input placeholder="Confirme sua senha" class="input" type="password" name="checkPass" />

            <input class="button" type="submit" value="Cadastrar" />

            <a href="<?=$base;?>/sair.php">Volta para página de login</a>
        </form>
    </section>
    <script>
        setTimeout(()=>{
            document.querySelector('.flash').classList.add("flash-hide");
        },2000);
    </script>
</body>
</html>