<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
<div id="content">
    <form action="<?=base_url();?>admin/news/edit/<?=$data['id']?>/<?=$parent_id;?>" method="post">
        <table class="admin_module_form">
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
            <tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$data['title'];?>' /></td></tr>
            <tr><td class="admin_module_form_title">показывать название</td><td><input type="checkbox" name="show_title" class="admin_module_form_checkbox styled" <?=!empty($data['show_title']) && $data['show_title'] == '1' ? 'checked' : '';?> /></td></tr>
            <tr><td class="admin_module_form_title">включить rss</td><td><input type="checkbox" name="rss" class="admin_module_form_checkbox styled" <?=!empty($data['rss']) && $data['rss'] == '1' ? 'checked' : '';?> /></td></tr>
            <tr><td class="admin_module_form_title">новостей на страницу</td><td><input type="text" name="count_per_page" value='<?=$data['count_per_page'];?>' /></td></tr>

            <tr>
                <td class="admin_module_form_title">ширина изображения</td>
                <td><input type="text" name="resize_width" value='<?=$data['resize_width'];?>'/></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">высота изображения</td>
                <td><input type="text" name="resize_height" value='<?=$data['resize_height'];?>'/></td>
            </tr>

            <tr>
                <td colspan="2"><input type="submit" value="сохранить" class="g-button" style="float: right; margin-right: 105px;"/></td>
            </tr>
        </table>
    </form>
</div>