    <div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
    <div class="admin_module_menu">
        <a href="<?=base_url();?>admin/banner/show/<?=$parent_id;?>" class="g-button">вернуться</a>
    </div>
    <div id="content">
        <form action="<?=base_url();?>admin/banner/add_image/<?=$parent_id;?>" method="post" enctype="multipart/form-data">
            <table class="admin_module_form">
                <tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
                <tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='1' /></td></tr>
                <tr><td class="admin_module_form_title">ссылка</td><td><input type="text" name="link" value='' /></td></tr>
                <tr><td class="admin_module_form_title">открывать в новой вкладке</td><td><input type="checkbox" name="link_new_window[]" value='' /></td></tr>

                <tr>
                    <td class="admin_module_form_title">порядок</td>
                    <td><input type="text" name="priority" value='' /></td>
                </tr>

                <tr><td class="admin_module_form_title">изображение</td><td><input type="file" name="image" size="255" />&nbsp;<strong><?= BANNER_MAIN_IMAGE_TOOLTIP; ?></strong></td></tr>
                <tr><td class="admin_module_form_title">текст</td><td><textarea class="ckeditor" id="description" name="description"><?=$this->input->post('description');?></textarea></td></tr>
                <tr>
                    <td colspan="2"><input type="submit" value="сохранить" class="g-button" style="float: right; margin-right: 105px;"/></td>
                </tr>
            </table>
        </form>
    </div>