<p class="error">
    <?= validation_errors(); ?>
</p>

<h1>
    <?= $fb_form->title; ?>
</h1>

<form id="contact_<?= $fb_form->id; ?>" action="" method="post">
    <? foreach ( $fb_fields as $key => $field ) : ?>
        <? $field_name = "fname_{$fb_form->id}_{$key}"; ?>
        <?= $field->title; ?>
        <? if ( $field->type == 4 ) : ?>
            <textarea name="<?= $field_name; ?>" placeholder="<?= $field->title; ?>"><?= set_value($field_name); ?></textarea>
            <br>
        <? elseif ( $field->type == 5 ) : ?>
            <select name="<?= $field_name; ?>">
                <? $cur_fields = explode(',', $field->selector_val); ?>
                <? foreach ( $cur_fields as $key => $value ) : ?>
                    <option value="<?= $value; ?>"><?= $value; ?></option>
                <? endforeach; ?>
            </select>
            <br>
        <? else: ?>
            <input type="text" name="<?= $field_name; ?>" placeholder="<?= $field->title; ?>" value='<?= set_value($field_name); ?>'>
            <br>
        <? endif; ?>
    <? endforeach; ?>
    <input type="submit" value="Отправить" />
</form>