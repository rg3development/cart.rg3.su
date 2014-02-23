<div class="admin_module_title">
    <h4><?=!empty($module_title) ? $module_title : '';?></h4>
</div>
<div id="content">
    <form action="<?= base_url('admin/settings'); ?>" method="post" enctype="multipart/form-data">
        <table class="admin_module_form">
            <tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
            <tr>
                <td class="admin_module_form_title">загрузить логотип</td>
                <td>
                    <input type="file" name="SITE_LOGO" size="255" /><br/>
                    <? if (!empty($settings[5]['logo'])) : ?>
                        <img src="<?=$settings[5]['logo'];?>"/>
                    <? endif; ?>
                </td>
            </tr>
            <tr><td class="admin_module_form_title">название</td><td><input type="text" name="SITE_TITLE" size="255" value="<?=!empty($settings[0]['value']) ? $settings[0]['value'] : ''?>" /></td></tr>
            <tr><td class="admin_module_form_title">описание</td><td><textarea name="SITE_DESCRIPTION"><?=!empty($settings[1]['value']) ? $settings[1]['value'] : ''?></textarea></td></tr>
            <tr><td class="admin_module_form_title">ключевые слова</td><td><input type="text" name="SITE_KEYWORDS" value="<?=!empty($settings[2]['value']) ? $settings[2]['value'] : ''?>"/></td></tr>
            <tr><td class="admin_module_form_title">укажите отправителя</td><td><input type="text" name="EMAIL" size="255" value="<?=!empty($settings[3]['value']) ? $settings[3]['value'] : ''?>"/></td></tr>
            <tr><td class="admin_module_form_title">email пересылки</td><td><input type="text" name="MY_EMAIL" size="255" value="<?=!empty($settings[4]['value']) ? $settings[4]['value'] : ''?>"/></td></tr>

            <tr>
                <td class="admin_module_form_title">счетчики</td>
                <td>
                    <textarea name="SITE_COUNTERS"><?= ( !empty($settings[6]['value']) ) ? $settings[6]['value'] : '' ; ?></textarea>
                </td>
            </tr>

            <tr>
                <td class="admin_module_form_title"></td>
                <td><input type="submit" value="сохранить" class="admin_module_form_submit" /></td>
            </tr>
        </table>
     </form>
</div>