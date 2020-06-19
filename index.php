<?php
require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'home';
$firtName = current(explode(" ", $userInfo->name));

//Composição do feed
$postDao = new PostDaoMysql($pdo);
$feed = $postDao->getHomeFeed($userInfo->id);
/*
echo "<pre>";
print_r($feed);
echo "</pre>";
*/
require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed mt-10">
            
    <div class="row">
        <div class="column pr-5">
            <?php require 'partials/feed_editor.php';?>                        
            
            <?php foreach ($feed as $item){
                require 'partials/feed_item.php';                        
            }?>

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