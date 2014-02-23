<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div class="admin_module_menu">
		<a href="<?=base_url();?>admin/news/show/<?=$parent_id;?>" class="g-button" style="margin-right: 105px;">назад</a>
	</div>
	<div id="content">
		<form action="<?=base_url();?>admin/news/edit_news/<?=$data['id'];?>/<?=$parent_id;?>" method="post" enctype="multipart/form-data">
			<table class="admin_module_form">
				<tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
				<tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$data['title'];?>' /></td></tr>
				<tr><td class="admin_module_form_title">анонс</td><td><textarea id="anno" name="anno"><?=$data['anno'];?></textarea></td></tr>

				<tr>
					<td class="admin_module_form_title">использовать специальную ссылку</td>
					<td>
						<input type="checkbox" name="is_spec_link" <?=!empty($data['is_spec_link']) && $data['is_spec_link'] == '1' ? 'checked' : '';?> />
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
					<td><textarea class="ckeditor" id="description" name="description"><?=$data['description'];?></textarea></td>
				</tr>
				<tr>
					<td class="admin_module_form_title">дата новости(дд-мм-гггг чч:мм)</td>
					<td>
						<input type="text" name="user_day" maxlength="2" value='<?=$data['user_day'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>-
						<input type="text" name="user_month" maxlength="2" value='<?=$data['user_month'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>-
						<input type="text" name="user_year" maxlength="4" value='<?=$data['user_year'];?>' style="width:30px !important; margin: 0px 3px 0px 0px;"/>&nbsp;
						<input type="text" name="user_hour" maxlength="2" value='<?=$data['user_hour'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>:
						<input type="text" maxlength="2" name="user_min" value='<?=$data['user_min'];?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>
						&nbsp;(дд-мм-гггг чч:мм)
					</td>
				</tr>
				<tr><td class="admin_module_form_title">изображение<br/>анонса</td>
					<td>
						<select name="inner_image">
							<option value="0" <?=!$data['inner_image'] ? 'selected' : '';?>>не отображать в теле новости</option>
							<option value="1" <?=$data['inner_image'] ? 'selected' : '';?>>отображать в теле новости</option>
						</select>
					</td>
				</tr>
				<tr><td class="admin_module_form_title">изображение<br/>новости</td>
					<td>
						<select name="inner_position">
							<option value="left" <?=!$data['inner_position'] == 'left' ? 'selected' : '';?>>изображение слева</option>
							<option value="right" <?=$data['inner_position'] == 'right' ? 'selected' : '';?>>изображение справа</option>
						</select>
					</td>
				</tr>
				<tr><td class="admin_module_form_title">изображение</td><td><input type="file" name="image" size="255" />&nbsp;<strong><?= NEWS_IMAGE_TOOLTIP; ?></strong></td></tr>
				<?php if (!empty($data['image']) && $data['image'] != '_thumb') : ?>
				<tr><td></td><td><img src="<?=$data['path'].'/'.$data['image'];?>"/></td></tr>
				<tr><td class="admin_module_form_title" colspan="2">для удаления изображения <a href="/admin/news/delete_image/<?=$data['id'];?>/<?=$parent_id;?>">нажмите здесь</a></td></tr>
				<?php endif; ?>
				<tr>
					<td colspan="2"><input type="submit" value="сохранить" class="g-button" style="float: right;"/></td>
				</tr>
			</table>
		</form>
	</div>