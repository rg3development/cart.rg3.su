<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= base_url('admin/feedback'); ?>">
                <button class="styler">к списку ФОС</button>
            </a>
        </li>
    </ul>
    <form action="<?= base_url('admin/feedback/edit/'.$form->id); ?>" method="post">

        <table class="admin_module_form">
            <tr>
                <td class="admin_module_form_title"></td>
                <td class="admin_error_message"><?=validation_errors();?></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">родительская страница</td>
                <td>
                    <select name="parent_id" class="page_select_list">
                        <option value="0">не выбрано</option>
                        <?= $page_select; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title">название</td>
                <td><input type="text" name="title" value='<?= $form->title; ?>' /></td>
            </tr>

            <tr>
                <td class="admin_module_form_title">тема письма</td>
                <td><input type="text" name="email_subject" value='<?= $form->email_subject; ?>' /></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">получатель (email)</td>
                <td><input type="text" name="email_to" value='<?= $form->email_to; ?>' /></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">отправитель (email)</td>
                <td><input type="text" name="email_from" value='<?= $form->email_from; ?>' /></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">отправитель (имя)</td>
                <td><input type="text" name="email_name" value='<?= $form->email_name; ?>' /></td>
            </tr>

            <tr>
                <td class="admin_module_form_title">Поля формы</td>
                <td>
                    <input type="button" class="styler addField" value="Добавить" />
                    <input type="button" class="styler delField" value="Удалить" />
                    <div class="fb_form">
                        <? $ff_required_key = 0; ?>
                        <? foreach ( $field_list as $key => $field ) : ?>
                            <div class="user_field">
                                <input type="hidden" class="ff_count" name="ff_count[]" value="<?= $key; ?>" />
                                Название: <input type="text" name="ff_title[]"  value="<?= $field->title; ?>" style="width: 145px;" />
                                <select class="ff_type" name="ff_type[]">
                                    <optgroup label="Тип поля">
                                        <option value="1" <?= ( $field->type == 1 ) ? 'selected="selected"' : '' ; ?>>строка</option>
                                        <option value="2" <?= ( $field->type == 2 ) ? 'selected="selected"' : '' ; ?>>email</option>
                                        <option value="3" <?= ( $field->type == 3 ) ? 'selected="selected"' : '' ; ?>>телефон</option>
                                        <option value="4" <?= ( $field->type == 4 ) ? 'selected="selected"' : '' ; ?>>текст</option>
                                        <option value="5" <?= ( $field->type == 5 ) ? 'selected="selected"' : '' ; ?>>селектор</option>
                                    </optgroup>
                                </select>
                                Обязательное: <input type="checkbox" name="ff_required[]" value="<?= $key; ?>" <?= ( $field->required ) ? 'checked="checked"' : ''; ?> />
                                <span class="last_span">
                                    <? if ( $field->type == 5 ) : ?>
                                        <input type="text" class="selector_val" name="selector_val[]" placeholder="Введите значения через запятую" value="<?= $field->selector_val; ?>">
                                        <input type="hidden" name="selector_index[]" value="<?= $key; ?>">
                                    <? endif; ?>
                                </span>
                            </div>
                            <? $ff_required_key = $key; ?>
                        <? endforeach; ?>
                    </div>
                </td>
            </tr>

        </table>

        <ul class="module_menu">
            <li>
                <input type="submit" class="styler" value="Сохранить">
            </li>
        </ul>
    </form>
</div>

<script language="javascript">

    var ff_count = <?= $ff_required_key + 1; ?>;

    $('input.addField').click(function() {
        $('div.fb_form').append(
            '<div class="user_field">' +
                '<input type="hidden" class="ff_count" name="ff_count[]" value="' + ff_count + '" />' +
                'Название: <input type="text" name="ff_title[]" style="width: 145px;" />' +
                '<select class="ff_type" name="ff_type[]">' +
                    '<optgroup label="Тип поля">' +
                        '<option value="1">строка</option>' +
                        '<option value="2">email</option>' +
                        '<option value="3">телефон</option>' +
                        '<option value="4">текст</option>' +
                        '<option value="5">селектор</option>' +
                    '</optgroup>' +
                '</select>' +
                'Обязательное: <input type="checkbox" name="ff_required[]" value="' + ff_count + '" />' +
                '<span class="last_span"></span>' +
            '</div>'
        );
        $('input, select').styler();
        ff_count++;
    });

    $('input.delField').click(function() {
        var count = $('div.fb_form div.user_field').length;
        if ( count > 0 )
        {
            $('div.fb_form div.user_field:last').remove();
            ff_count--;
            $('input:file').styler();
        } else {
            alert('Не возможно удалить поле!');
        }
    });

    $('select.ff_type').live('change', function () {
        var ff_type = $(this).attr('value');
        if ( ff_type == 5 )
        {
            var last = $(this).parent().find('.last_span');
            var cur_count = $(this).parent().find('.ff_count').val();
            last.append(
                '<input type="text" class="selector_val" name="selector_val[]" placeholder="Введите значения через запятую">' +
                '<input type="hidden" name="selector_index[]" value="' + cur_count + '">'
            );
        } else {
            var last = $(this).parent().find('.last_span > input').remove();
        }
    });

</script>