<? if (!empty($gallery)) :?>
<?=!empty($paginator) ? '<div class="paginator">'.$paginator.'</div>' : ''; ?>
<div class="gallery-block">
    <ul class="gallery">
        <? foreach ($gallery as $image) :?>
        <li><a href="<?=$path.'/'.$image['filename'];?>" class="gallery-link"><img src="<?=$path.'/'.$image['thumbnail'];?>" title="<?=$image['title'];?>" class="photo" alt=""></a><span class="photo-title"><?=$image['title'];?></span></li>
        <? endforeach; ?>
    </ul>
</div>
<div class="clear"></div>
<?=!empty($paginator) ? '<div class="paginator">'.$paginator.'</div>' : ''; ?>
<script type="text/javascript">
    $('.gallery-link').lightBox({
        imageLowading: '/js/plugins/lightbox/images/loading.gif',
        imageBtnClose: '/js/plugins/lightbox/images/close.gif',
        imageBtnPrev: '/js/plugins/lightbox/images/prev.gif',
        imageBtnNext: '/js/plugins/lightbox/images/next.gif',
    });
</script>
<? endif; ?>