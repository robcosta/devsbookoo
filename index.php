<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'home';
$firtName = current(explode(" ", $userInfo->name));

//Composição do feed
$postDao = new PostDaoMysql($pdo);
$feed = $postDao->getHomeFeed($userInfo->id);

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
    </section>
    <section class="marketing mt-10">        
            <div class="column side pl-5">
                <div class="box banners">
                    <div class="box-header">
                        <div class="box-header-text">Patrocinios</div>
                        <div class="box-header-buttons">
                            
                        </div>
                    </div>
                    <div class="box-body">
                        <a href="https://www.google.com"><img src="https://www.google.com/logos/google.jpg" /></a>
                        <a href="https://www.facebook.com"><img src="data:image/jpeg;base64,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" /></a>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body m-10">
                        Criado com ❤️ por B7Web
                    </div>
                </div>
            </div>            
    </section>   
   
</section>


<?php require 'partials/footer.php';?>