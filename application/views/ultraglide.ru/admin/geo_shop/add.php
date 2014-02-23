<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">

    <ul class="module_menu">
        <li>
            <a href="<?= base_url('admin/geo_shops'); ?>">
                <button class="styler">к списку</button>
            </a>
        </li>
    </ul>

    <form action="<?= base_url("admin/geo_shops/add"); ?>" method="post">
        <table class="admin_module_form">

            <tr>
                <td class="admin_module_form_title"></td>
                <td class="admin_error_message">
                    <?= validation_errors(); ?>
                </td>
            </tr>

            <tr>
                <td class="admin_module_form_title">название</td>
                <td>
                    <input type="text" name="title" value="<?= set_value('title', ''); ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">порядок</td>
                <td>
                    <input type="text" name="priority" value="<?= set_value('priority', 0); ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">показывать</td>
                <td>
                    <input type="checkbox" name="show" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">широта</td>
                <td>
                    <input type="text" name="latitude" value="<?= set_value('latitude', ''); ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">долгота</td>
                <td>
                    <input type="text" name="longitude" value="<?= set_value('longitude', ''); ?>" />
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="submit" value="сохранить" class="g-button" style="float: right; margin-right: 105px;" />
                </td>
            </tr>

        </table>
    </form>

</div>