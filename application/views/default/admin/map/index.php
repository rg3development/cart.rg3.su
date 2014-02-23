<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= base_url('admin/map/add'); ?>">
                <button class="styler">создать страницу</button>
            </a>
        </li>
    </ul>
    <ul class="admin_list admin_mainlist">
        <?= $map; ?>
    </ul>
    <ul class="module_menu">
        <li>
            <a href="#to_top">наверх</a>
        </li>
    </ul>
</div>