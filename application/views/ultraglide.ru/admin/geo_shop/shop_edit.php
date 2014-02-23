<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">

	<ul class="module_menu">
        <li>
            <a href="<?= base_url("admin/geo_shops/show/{$parent_id}"); ?>">
                <button class="styler">к списку</button>
            </a>
        </li>
    </ul>

    <form action="<?= base_url("admin/geo_shops/edit_shop/{$shop_item->id}/{$parent_id}"); ?>" method="post">
    	<input type="hidden" name="town_id" value="<?= $parent_id; ?>" />
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
                    <input type="text" name="title" value="<?= $shop_item->title; ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">порядок</td>
                <td>
                    <input type="text" name="priority" value="<?= $shop_item->priority; ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">адрес</td>
                <td>
                    <input type="text" name="address" value="<?= $shop_item->address; ?>" />
                    <button class="styler" id="check_shop">Проверить</button>
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">телефон</td>
                <td>
                    <input type="text" name="phones" value="<?= $shop_item->phones; ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">время работы</td>
                <td>
                    <input type="text" name="work_time" value="<?= $shop_item->work_time; ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">широта</td>
                <td>
                    <input type="text" name="latitude" value="<?= $shop_item->latitude; ?>" />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">долгота</td>
                <td>
                    <input type="text" name="longitude" value="<?= $shop_item->longitude; ?>" />
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

<script type="text/javascript">
    $(document).ready(function() {

        $('#check_shop').click(function () {
            var cur_address = $('input[name=address]').val();
            var cur_town    = $('input[name=town_id]').val();
            if ( cur_address.length > 0 )
            {
                $.post('/admin/geo_shops/check_shop', { town_id: cur_town, address: cur_address },
                    function (data) {
                        console.log(data);
                        $('input[name=latitude]').val(data.xlat);
                        $('input[name=longitude]').val(data.xlong);
                }, 'json');
            } else {
                alert('Введите полный адрес');
            }
            return false;
        });

    });
</script>