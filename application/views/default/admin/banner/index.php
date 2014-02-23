	<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div id="content">
		<div class="banners_module">
			<form action="<?=base_url();?>admin/banner" method="post">
				<div class="admin_module_form">родительская страница
					<select name="parent_id" class="page_select_list">
						<option value="0">не выбрано</option>
						<?=$page_select;?>
					</select>
					<input type="submit" value="выбрать" class="g-button"/>
				</div>
			</form>
			<div class="admin_module_list">
				<ul class="admin_list">
					<?php if (!empty($list)) :?>
					<?php foreach ($list as $banner) :?>
					<li id="text<?=$banner->id;?>" onmouseover="$(this).children().children().css('color', '#0099cc')" onmouseout="$(this).children().children().css('color', '#666666')">
						<em><a href="<?=base_url();?>admin/banner/show/<?=$banner->id;?>/<?=$parent_id;?>"><?=$banner->title;?></a></em>
						<a href="<?=base_url();?>admin/banner/edit/<?=$banner->id;?>/<?=$parent_id;?>" class="admin_form_action_page" title="редактировать"><img title="редактировать" alt="редактировать" src="<?=base_url();?>/www_admin/img/icon_edit_1.5.png"/></a>
						<div class="clear"></div>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>