<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
    <div class="admin_module_menu">
        <a href="<?=base_url();?>admin/response/show/<?=$parent_id;?>" class="g-button" style="margin-right: 105px;">назад</a>
    </div>
    <div id="content">
        <form action="<?=base_url();?>admin/response/edit_response/<?=$data['id'];?>/<?=$parent_id;?>" method="post" enctype="multipart/form-data">
            <table class="admin_module_form">
                <tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
                <tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$data['title'];?>' /></td></tr>
                <tr>
                    <td class="admin_module_form_title">автор</td>
                    <td>
                        <input type="text" name="author" value='<?=$data['author'];?>' />
                    </td>
                </tr>
                <tr>
                    <td class="admin_module_form_title">использовать специальную ссылку</td>
                    <td>
                        <input type="checkbox" name="is_spec_link" <?=!empty($data['is_spec_link']) && $data['is_spec_link'] == '1' ? 'checked' : '';?> />
                    </td>
                </tr>
                <tr>
                    <td class="admin_module_form_title">открывать в новом окне</td>
                    <td>
                        <input type="checkbox" name="link_new_window" <?=!empty($data['link_new_window']) && $data['link_new_window'] == '1' ? 'checked' : '';?> />
                    </td>
                </tr>
                <tr>
                    <td class="admin_module_form_title">специальная ссылка</td>
                    <td>
                        <input type="text" name="spec_link" value='<?=$data['spec_link'];?>' />
                    </td>
                </tr>
                <tr>
                    <td class="admin_module_form_title">описание</td>
                    <td><textarea id="description" name="description"><?=$data['description'];?></textarea></td>
                </tr>
                <tr>
                    <td class="admin_module_form_title">дата (дд-мм-гггг чч:мм)</td>
                    <td>
                        <input type="text" name="user_day" maxlength="2" value='<?=$data['user_day'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>-
                        <input type="text" name="user_month" maxlength="2" value='<?=$data['user_month'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>-
                        <input type="text" name="user_year" maxlength="4" value='<?=$data['user_year'];?>' style="width:30px !important; margin: 0px 3px 0px 0px;"/>&nbsp;
                        <input type="text" name="user_hour" maxlength="2" value='<?=$data['user_hour'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>:
                        <input type="text" maxlength="2" name="user_min" value='<?=$data['user_min'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>
                        &nbsp;(дд-мм-гггг чч:мм)
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="сохранить" class="g-button" style="float: right;"/></td>
                </tr>
            </table>
        </form>
    </div>