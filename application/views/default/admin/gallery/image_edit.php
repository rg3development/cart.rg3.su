
<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
    <div class="admin_module_menu">
        <a href="<?=base_url();?>admin/gallery/show/<?=$parent_id;?>" class="g-button">вернуться</a>
    </div>
    <div id="content">
        <form action="<?=base_url();?>admin/gallery/edit_image/<?=$data['id'];?>/<?=$parent_id;?>" method="post" enctype="multipart/form-data">
            <table class="admin_module_form">
                <tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
                <tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$data['title'];?>' /></td></tr>

                <tr>
                    <td class="admin_module_form_title">порядок</td>
                    <td><input type="text" name="priority" value='<?= $data['priority']; ?>' /></td>
                </tr>

                <tr><td class="admin_module_form_title">изображение</td><td><input type="file" name="image" size="255" />&nbsp;<strong><?= GALLERY_IMAGE_TOOLTIP; ?></strong></td></tr>
                <?php if (!empty($data['image']) && $data['image'] != '_thumb') : ?>
                <tr><td></td><td><img src="<?=$data['path'].'/'.$data['image'];?>"/></td></tr>
                <?php endif; ?>
                <tr><td class="admin_module_form_title">описание</td><td><textarea class="ckeditor" id="description" name="description"><?=$data['description'];?></textarea></td></tr>
                <tr>
                    <td colspan="2"><input type="submit" value="сохранить" class="g-button" style="float: right; margin-right: 105px;"/></td>
                </tr>
            </table>
        </form>
    </div>