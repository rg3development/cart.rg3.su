<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= base_url("admin/geo_shops/add_shop/{$parent_id}"); ?>">
                <button class="styler">добавить магазин</button>
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/geo_shops'); ?>">
                <button class="styler">к списку</button>
            </a>
        </li>
    </ul>
    <ul class="admin_list admin_mainlist">
        <h6>Список магазинов:</h6>
        <ul class="admin_list">
            <? foreach ( $shop_list as $index => $shop_item ) : ?>
                <li>
                    <div class="menuline clearfix">
                        <span class="left">
                            <a href="<?= base_url("admin/geo_shops/edit_shop/{$shop_item->id}/{$parent_id}"); ?>">
                                <?= $shop_item->title; ?>
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= base_url("admin/geo_shops/delete_shop/{$shop_item->id}/{$parent_id}"); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="удалить">
                                <img title="удалить" alt="удалить" src="<?= base_url('/www_admin/img/icon_delete_1.5.png'); ?>"/>
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= base_url("admin/geo_shops/edit_shop/{$shop_item->id}/{$parent_id}"); ?>" class="admin_form_action_page" title="редактировать">
                                <img title="редактировать" alt="редактировать" src="<?= base_url('/www_admin/img/icon_edit_1.5.png'); ?>"/>
                            </a>
                        </span>
                        <span class="right priority" title="порядок">
                            <?= $shop_item->priority; ?>
                        </span>
                        <span class="right">
                            <?= $shop_item->address; ?>
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