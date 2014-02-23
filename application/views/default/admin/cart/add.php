	<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div id="content">
		<form action="<?=base_url();?>admin/cart/add/<?=$parent_id;?>" method="post">
			<table class="admin_module_form cart_module_edit">
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
				<tr><td class="admin_module_form_title">название</td><td><input type="cart" name="title" value='<?=$this->input->post('title');?>' /></td></tr>
				<tr><td class="admin_module_form_title">показывать название</td><td><input type="checkbox" name="show_title" class="admin_module_form_checkbox styled"<?=$this->input->post('show_title') == 'on' ? 'checked' : '';?> /></td></tr>
				<tr><td class="admin_module_form_title">текст</td><td><cartarea id="description" name="description"><?=$this->input->post('description');?></cartarea></td></tr>
				<tr>
					<td class="admin_module_form_title"></td>
					<td class="admin_td_submit"><input type="submit" value="Сохранить" class="admin_module_form_submit g-button" /></td>
				</tr>
			</table>
		</form>
	</div>
	<script type="cart/javascript">
		$(document).ready(
			function() {
				jQuery('#description').redactor({convertDivs: true, convertLinks: false, observeImages: true, fileUpload: '/admin/map/imeravi_upload_file', imageUpload: '/admin/map/imeravi_upload_image'});
			}
		);
	</script>