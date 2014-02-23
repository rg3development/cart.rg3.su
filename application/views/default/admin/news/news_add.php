	<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div class="admin_module_menu">
		<a href="<?=base_url();?>admin/news/show/<?=$parent_id;?>" class="g-button" style="margin-right: 105px;">назад</a>
	</div>
	<div id="content">
		<form action="<?=base_url();?>admin/news/add_news/<?=$parent_id;?>" method="post" enctype="multipart/form-data">
			<table class="admin_module_form">
				<tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
				<tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$this->input->post('title');?>' /></td></tr>
				<tr><td class="admin_module_form_title">анонс</td><td><textarea id="anno" name="anno"><?=$this->input->post('anno');?></textarea></td></tr>

				<tr>
					<td class="admin_module_form_title">использовать специальную ссылку</td>
					<td>
						<input type="checkbox" name="is_spec_link" />
					</td>
				</tr>
				<tr>
					<td class="admin_module_form_title">специальная ссылка</td>
					<td>
						<input type="text" name="spec_link" value='<?=$this->input->post('spec_link');?>' />
					</td>
				</tr>

				<tr><td class="admin_module_form_title">описание</td><td><textarea class="ckeditor" id="description" name="description"><?=$this->input->post('description');?></textarea></td></tr>
				<tr>
					<td class="admin_module_form_title">дата новости</td>
					<td>
						<input type="text" name="user_day" maxlength="2" value='<?=!$this->input->post('user_day') ? date("d") : $this->input->post('user_day');?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>-
						<input type="text" name="user_month" maxlength="2" value='<?=!$this->input->post('user_month') ? date("m") : $this->input->post('user_month');?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>-
						<input type="text" name="user_year" maxlength="4" value='<?=!$this->input->post('user_year') ? date("Y") : $this->input->post('user_year');?>' style="width:30px !important; margin: 0px 3px 0px 0px;"/>&nbsp;
						<input type="text" name="user_hour" maxlength="2" value='<?=!$this->input->post('user_hour') ? date("H") : $this->input->post('user_hour');?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>:
						<input type="text" maxlength="2" name="user_min" value='<?=!$this->input->post('user_min') ? date("i") : $this->input->post('user_min');?>' style="width:20px !important; margin: 0px 3px 0px 0px;"/>
						&nbsp;(дд-мм-гггг чч:мм)
					</td>
				</tr>
				<tr><td class="admin_module_form_title">изображение<br/>анонса</td>
					<td>
						<select name="inner_image">
							<option value="0" <?=!$this->input->post('inner_image') ? 'selected' : '';?>>не отображать в теле новости</option>
							<option value="1" <?=$this->input->post('inner_image') ? 'selected' : '';?>>отображать в теле новости</option>
						</select>
					</td>
				</tr>
				<tr><td class="admin_module_form_title">изображение<br/>новости</td>
					<td>
						<select name="inner_position">
							<option value="left" <?=$this->input->post('inner_position') == 'left' ? 'selected' : '';?>>слева</option>
							<option value="right" <?=$this->input->post('inner_position') == 'right' ? 'selected' : '';?>>справа</option>
						</select>
					</td>
				</tr>
				<tr><td class="admin_module_form_title">изображение</td><td><input type="file" name="image" size="255" />&nbsp;<strong><?= NEWS_IMAGE_TOOLTIP; ?></strong></td></tr>
				<tr>
					<td colspan="2"><input type="submit" value="сохранить" class="g-button" style="float: right;"/></td>
				</tr>
			</table>
		</form>
	</div>