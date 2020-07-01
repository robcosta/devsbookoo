<?php
require_once 'feed-item-script.php';
?>
<div class="box feed-item" data-id="<?=$item->id;?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?=$base;?>/perfil.php?id=<?=$item->user->id;?>"><img src="<?=$base;?>/media/avatars/<?=$item->user->avatar;?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?=$base;?>/perfil.php?id=<?=$item->user->id;?>"><span class="fidi-name"><?=$item->user->name;?></span></a>
                <span class="fidi-action">
                    <?=$item->type == "text" ? "Fez um post" : "Postou uma Foto";?>
                </span>
                <br/>
                <span class="fidi-date"><?=date('d/m/Y H:i', strtotime($item->created_at));?></span>
            </div>
            <div class="feed-item-head-btn">
                <img src="<?=$base;?>/assets/images/more.png" />
            </div>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
            <?= str_replace('&#13;&#10;', '<br>', $item->body);?>
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?=$item->liked ?'on' : '';?>"><?=$item->likeCount;?></div>
            <div class="msg-btn"><?=count($item->comments);?></div>
        </div>
        <div class="feed-item-comments">
            <div class="feed-item-comments-area">
                <?php foreach($item->comments as $comment):?>
                    <div class="fic-item row m-height-10 m-width-20">
                        <div class="fic-item-photo">
                            <a href="<?=$base;?>/perfil.php?id=<?=$comment->user->id;?>"><img src="<?=$base;?>/media/avatars/<?=$comment->user->avatar;?>" /></a>
                        </div>
                        <div class="fic-item-info">
                            <a href="<?=$base;?>/perfil.php?id=<?=$comment->user->id;?>"><?=$comment->user->name;?></a>
                            <?=$comment->body;?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?=$base;?>/perfil.php/id=<?=$userInfo->id;?>"><img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" /></a>
                </div>
                <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
            </div>
        </div>
    </div>
</div>