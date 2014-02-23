<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= base_url('admin/geo_shops/add'); ?>">
                <button class="styler">добавить город</button>
            </a>
        </li>
    </ul>
    <ul class="admin_list admin_mainlist">
        <h6>Список городов:</h6>
        <ul class="admin_list">
            <? foreach ( $shop_list as $index => $town_item ) : ?>
                <li>
                    <div class="menuline clearfix">
                        <span class="left">
                            <a href="<?= base_url("admin/geo_shops/show/{$town_item->id}"); ?>">
                                <?= $town_item->title; ?>
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= base_url("admin/geo_shops/delete/{$town_item->id}"); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="удалить">
                                <img title="удалить" alt="удалить" src="<?= base_url('/www_admin/img/icon_delete_1.5.png'); ?>"/>
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= base_url("admin/geo_shops/edit/{$town_item->id}"); ?>" class="admin_form_action_page" title="редактировать">
                                <img title="редактировать" alt="редактировать" src="<?= base_url('/www_admin/img/icon_edit_1.5.png'); ?>"/>
                            </a>
                        </span>
                        <span class="right show" title="показывать">
                            <?= ( $town_item->show ) ? '+' : '-'; ?>
                        </span>
                        <span class="right priority" title="порядок">
                            <?= $town_item->priority; ?>
                        </span>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    </ul>
    <ul class="module_menu">
        <li>
            <a href="#to_top">наверх</a>
        </li>
    </ul>
</div>