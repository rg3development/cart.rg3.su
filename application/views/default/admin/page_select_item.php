<?php if ($page->id != $this->uri->segment(4)) : ?>
    <option value="<?=$page->id;?>" <?=$page->id == $active_id ? 'selected' : '' ?> >
        <?php if ($level > 0) for ($j = 0; $j < $level; $j++) echo "-"; ?>
        <?=$page->title;?> (<?=$page->alias;?>)
    </option>
    <?=$submenu;?>
<?php endif; ?>