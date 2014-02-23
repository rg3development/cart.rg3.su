<div class="center">
    <? if ( $page_info['show_title'] ) : ?>
        <h1>
            <?= $page_info['title']; ?>
        </h1>
    <? endif; ?>

    <?= $content; ?>

</div>