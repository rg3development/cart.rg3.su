<?php if (!empty($news)) : ?>
<div class="list-block">
    <div class="list-item two-blocks">
        <div class="date"><span class="num"><?=date("d", (int)$news['created']);?></span>&nbsp;<?=get_month_name((int)date("m", $news['created']));?></div>
        <div class="text">
        <a href="<?=base_url().$page_url.'?news_id=0&per_page='.$offset?>"><?=$news['title'];?></a><br>
        <?=$news['description'];?>
        <a href="<?=base_url().$page_url.'?news_id=0&per_page='.$offset?>">вернуться к полноу списку статей...</a>
        </div>
    </div>
</div>
<?php endif; ?>