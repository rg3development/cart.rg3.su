<? if (!empty($item) && !empty($image)) :?>
	<a href="<?=$item->link?>" target="<?=$item->link_new_window ? "_blank" : "" ?>">
		<img class="inner_banner" src="<?=IMAGESRC.'banner/'.$image->getFilename()?>"/>
	</a>
<? endif;?>