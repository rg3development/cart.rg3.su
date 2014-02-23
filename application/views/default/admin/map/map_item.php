<li>
    <div class="menuline clearfix">
        <span class="left">
            <a id="page<?=$page->id;?>" href="<?= base_url("admin/map/edit/{$page->id}"); ?>">
                <?= $page->title; ?><?= ( $page->alias ) ? ' (' . $page->alias . ')' : ''; ?>
            </a>
        </span>
        <span class="right">
            <a href="<?= base_url("admin/map/delete/{$page->id}"); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="удалить">
                <img title="удалить" alt="удалить" src="<?= base_url('/www_admin/img/icon_delete_1.5.png'); ?>"/>
            </a>
        </span>
        <span class="right">
            <a href="<?= base_url("admin/map/edit/{$page->id}"); ?>" class="admin_form_action_page" title="редактировать">
                <img title="редактировать" alt="редактировать" src="<?= base_url('/www_admin/img/icon_edit_1.5.png'); ?>"/>
            </a>
        </span>
        <span class="right show" title="показывать">
            <?= ( $page->show ) ? '+' : '-'; ?>
        </span>
        <span class="right priority" title="порядок">
            <?= $page->priority; ?>
        </span>
        <span class="right url" title="url страницы">
            (<a href="<?= base_url($page->url); ?>" target="_blank">
                /<?= $page->url; ?>
            </a>)
        </span>
    </div>

    <div class="clearfix">
        <?= $submenu; ?>
    </div>
</li>