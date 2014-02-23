	<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div id="content">
		<div class="news_module">
			<div class="admin_module_form right_buttons">
				<a href="<?=base_url();?>admin/news/" class="g-button" style="margin-right: 0px;">назад</a>
				<a href="<?=base_url();?>admin/news/add_news/<?=$parent_id;?>" class="g-button">создать</a>
			</div>
			<div class="admin_module_list">
				<div class="admin_module_list">
					<ul class="admin_list photo_image_list">
					<?php if (!empty($list)) :?>
					<?php foreach ($list as $key => $item) :?>
						<li id="text<?=$item->id;?>" onmouseover="$(this).children().children().css('color', '#0099cc')" onmouseout="$(this).children().children().css('color', '#666666')">
							<a href="<?=base_url();?>admin/news/edit_news/<?=$item->id;?>/<?=$parent_id;?>" title="редактировать"><em><?=$item->title;?></em></a>
							<a href="<?=base_url();?>admin/news/delete_news/<?=$item->id;?>/<?=$parent_id;?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="удалить"><img src="<?=base_url();?>/www_admin/img/icon_delete_1.5.png"/></a>
							<a href="<?=base_url();?>admin/news/edit_news/<?=$item->id;?>/<?=$parent_id;?>" class="admin_form_action_page" title="редактировать"><img src="<?=base_url();?>/www_admin/img/icon_edit_1.5.png"/></a>
							<span class="admin_form_action_page"><?=date("Y-d-m H:i",$item->created);?></span>
							<div class="clear"></div>
						</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>