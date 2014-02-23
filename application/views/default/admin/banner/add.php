	<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div id="content">
		<form action="<?=base_url();?>admin/banner/add/<?=$parent_id;?>" method="post">
			<table class="admin_module_form banners_module_edit">
				<tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
				<tr><td class="admin_module_form_title">название</td><td><input type="text" name="title" value='<?=$this->input->post('title');?>' /></td></tr>
				<tr><td class="admin_module_form_title">активных баннеров</td><td><input type="text" name="count_per_page" value='<?=$this->input->post('count');?>' /></td></tr>
				<tr><td class="admin_module_form_title">ширина изображения</td><td><input type="text" name="resize_with" value='<?=$data['resize_with'];?>'/></td></tr>
				<tr><td class="admin_module_form_title">высота изображения</td><td><input type="text" name="resize_height" value='<?=$data['resize_height'];?>'/></td></tr>
				<tr>
					<td class="admin_module_form_title"></td>
					<td><input type="submit" value="сохранить" class="admin_module_form_submit g-button" /></td>
				</tr>
			</table>
		</form>
	</div>