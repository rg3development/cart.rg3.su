    <div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
    <div id="content">
        <form action="<?=base_url();?>admin/gallery/add/<?=$parent_id;?>" method="post">
            <table class="admin_module_form photo_module_edit">
                <tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
                <tr>
                    <td class="admin_module_form_title">родительская страница</td>
                    <td>
                        <select name="parent_id" class="page_select_list">
                            <option value="0">не выбрано</option>
                            <?=$page_select;?>
                        </select>
                    </td>
                </tr>
                <tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$this->input->post('title');?>' /></td></tr>
                <tr><td class="admin_module_form_title">показывать название</td><td><input type="checkbox" name="show_title" class="admin_module_form_checkbox styled" /></td></tr>
                <tr><td class="admin_module_form_title">элементов на страницу</td><td><input type="text" name="count_per_page" value='<?=$this->input->post('count');?>' /></td></tr>
                <tr>
                    <td class="admin_module_form_title">тип</td>
                    <td>
                        <select name="script_type" class="page_select_list">
                            <option value="0">lightbox</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td class="admin_module_form_title">ширина изображения</td>
                    <td><input type="text" name="resize_width" value='<?= set_value('resize_width', 0); ?>'/></td>
                </tr>
                <tr>
                    <td class="admin_module_form_title">высота изображения</td>
                    <td><input type="text" name="resize_height" value='<?= set_value('resize_height', 0); ?>'/></td>
                </tr>

                <tr>
                    <td class="admin_module_form_title"></td>
                    <td><input type="submit" value="сохранить" class="admin_module_form_submit g-button" /></td>
                </tr>
            </table>
        </form>
    </div>