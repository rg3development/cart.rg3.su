<div class="list-block search-block">
    <p class="search-label">введите запрос</p>
    <form action="" method="get">
    <div class="search-forma">
        <span class="text-input"><input type="text" class="text autoclear" name="s" placeholder="..." value="<?=!empty($_GET['s']) ? $_GET['s'] : '';?>"></span>
        <input type="submit" class="submit" title="" value="">
    </div>
    </form>
    <? if (sizeof($content) > 0) : ?>
    <? foreach ($content as $key => $row) : ?>
    <div class="list-item two-blocks">
        <a href="/<?=$row['url'];?>"><?=mb_substr($row['content'],0, 100);?></a><br/>
        <?=strip_tags($row['content']);?><br/>
        <a href="/<?=$row['url'];?>">(подробнее)</a>
    </div>
    <? endforeach; ?>
    <? endif;?>
</div>