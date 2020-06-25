<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'search';
$firtName = current(explode(" ", $userInfo->name));

$seachTerm = filter_input(INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if(!$seachTerm){
    header('Location: '.$base);
    exit;
}
$userDao = new UserDaoMysql($pdo);
$userSearch = $userDao->findByName($seachTerm);



require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed mt-10">
            
    <div class="row">
        <div class="column pr-5">
           <h3>Pesquisa por: <?=$seachTerm;?></h3>
           <div class="full-friend-list">
           <?php if(count($userSearch) > 0):?>
                    <?php foreach($userSearch as $item):?>
                        <div class="friend-icon">
                            <a href="<?=$base;?>/perfil.php?id=<?=$item->id;?>">
                                <div class="friend-icon-avatar">
                                    <img src="<?=$base;?>/media/avatars/<?=$item->avatar;?>" />
                                </div>
                                <div class="friend-icon-name">
                                    <?=$item->name;?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
            <?php endif;?>     
            </div>   
        </div>
        <div class="column side pl-5">
            <div class="box banners">
                <div class="box-header">
                    <div class="box-header-text">Patrocinios</div>
                    <div class="box-header-buttons">
                        
                    </div>
                </div>
                <div class="box-body">
                    <a href=""><img src="" /></a>
                    <a href=""><img src="" /></a>
                </div>
            </div>
            <div class="box">
                <div class="box-body m-10">
                    Criado com ❤️ por B7Web
                </div>
            </div>
        </div>
    </div>

</section>


<?php require 'partials/footer.php';?>