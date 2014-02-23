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
    <form action="<?= base_url('admin/feedback/add'); ?>" method="post">

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
                <td><input type="text" name="title" value='<?= set_value('title'); ?>' /></td>
            </tr>

            <tr>
                <td class="admin_module_form_title">тема письма</td>
                <td><input type="text" name="email_subject" value='<?= set_value('email_subject'); ?>' /></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">получатель (email)</td>
                <td><input type="text" name="email_to" value='<?= set_value('email_to'); ?>' /></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">отправитель (email)</td>
                <td><input type="text" name="email_from" value='<?= set_value('email_from'); ?>' /></td>
            </tr>
            <tr>
                <td class="admin_module_form_title">отправитель (имя)</td>
                <td><input type="text" name="email_name" value='<?= set_value('email_name'); ?>' /></td>
            </tr>

            <tr>
                <td class="admin_module_form_title">Поля формы</td>
                <td>
                    <input type="button" class="styler addField" value="Добавить" />
                    <input type="button" class="styler delField" value="Удалить" />
                    <div class="fb_form"></div>
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

    var ff_count = 0;

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