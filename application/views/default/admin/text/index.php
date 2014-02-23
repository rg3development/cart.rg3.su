	<div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
	<div id="content">
		<div class="text_module">
			<form action="" method="post">
				<div class="admin_module_form">родительская страница
					<select name="parent_id" class="page_select_list">
						<option value="0">не выбрано</option>
						<?=$page_select;?>
					</select>
					<input type="submit" value="выбрать" class="g-button"/>
					<input type="button" class="g-button" value="создать" onclick="location.href='/admin/text/add/'" />
				</div>
			</form>
			<ul class="admin_list">
				<?php if (!empty($list_text)) :?>
				<?php foreach ($list_text as $text) :?>
				<li id="text<?=$text->id;?>" onmouseover="$(this).children().children().css('color', '#0099cc')" onmouseout="$(this).children().children().css('color', '#666666')">
					<em><a href="<?=base_url();?>admin/text/edit/<?=$text->id;?>/<?=$parent_id;?>"><?=$text->title;?></a></em>
					<? if ($text->id != 36) : ?><a href="<?=base_url();?>admin/text/delete/<?=$text->id;?>/<?=$parent_id;?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="удалить"><img title="удалить" alt="удалить" src="<?=base_url();?>www_admin/img/icon_delete_1.5.png"/></a><? endif; ?>
					<a href="<?=base_url();?>admin/text/edit/<?=$text->id;?>/<?=$parent_id;?>" class="admin_form_action_page" title="редактировать"><img title="редактировать" alt="редактировать" src="<?=base_url();?>www_admin/img/icon_edit_1.5.png"/></a>
					<? if ($text->id != 36) : ?><a href="<?=base_url();?>admin/text/copy/<?=$text->id;?>/<?=$parent_id;?>" class="admin_form_action_page" title="копировать"><img title="копировать" alt="копировать" src="<?=base_url();?>www_admin/img/icon_copy_1.5.png"/></a><? endif; ?>
					<div class="clear"></div>
				</li>
				<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>