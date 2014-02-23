<? if (!empty($response_list)) :?>
    <?=!empty($paginator) ? '<div class="paginator">'.$paginator.'</div>' : ''; ?>
    <? foreach ($response_list as $key => $response) : ?>
    <div class="list-block responcies">
        <div class="list-item two-blocks">
            <div class="text">
                <span class="title-author"><a href="#"><?=$response['title'];?></a>, <?=$response['author'];?></span><br>
                <em><?=$response['description'];?></em><br>
                <? if ($response['is_spec_link']) :?>
                <a href="<?=$response['spec_link'];?>" target="<?=$response['link_new_window'] ? '_blank' : '_self';?>">(подробнее)</a>
                <? endif; ?>
            </div>
        </div>
    </div>
    <? endforeach; ?>
<? endif; ?>
