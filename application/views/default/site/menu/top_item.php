<?
/* current menu item */
/*

OLD (by url):
<?= ( strrpos($_SERVER['REQUEST_URI'], '/'.$page->url) !== false ) ? 'class="current"' : '' ; ?>

NEW (by page id):
<?= in_array($page->id, $params['parent_ids']) ? 'class="active"' : ''; ?>

*/
?>

<li <?= in_array($page->id, $params['parent_ids']) ? 'class="nav_over"' : ''; ?>>
    <a href="<?= substr($page->url,0,1) == '/' ? $page->url : '/'.$page->url; ?>" id="page<?= $page->id; ?>">
        <?= ( $page->show_alias && $page->alias ) ? $page->alias : $page->title; ?>
    </a>
    <? if ( $submenu ) : ?>
        <ul>
            <?= $submenu; ?>
        </ul>
    <? endif; ?>
</li>