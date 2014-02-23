<? if (!empty($news_list)) :?>
    <?=!empty($paginator) ? '<div class="paginator">'.$paginator.'</div>' : ''; ?>
    <div class="clear"></div>
    <div class="list-block">
    <? foreach ($news_list as $key => $news) : ?>
    <div class="list-item two-blocks">
        <div class="date"><span class="num"><?=date("d", (int)$news['created']);?></span>&nbsp;<?=get_month_name((int)date("m", $news['created']));?></div>
        <div class="text">
            <a href="<?='/'.$page_url.'?news_id='.$news['id'].'&per_page='.$offset;?>"><?=$news['title'];?></a><br>
            <?=strip_tags($news['anno']);?><br>
            <a href="<?='/'.$page_url.'?news_id='.$news['id'].'&per_page='.$offset;?>">(подробнее)</a>
        </div>
    </div>
    <? endforeach; ?>
    </div>
<? endif; ?>

