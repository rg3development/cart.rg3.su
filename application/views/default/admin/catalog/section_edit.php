<div class="admin_module_title">
  <h4><?= $module['title']; ?></h4>
</div>

<div id="content">
  <div class="text_module">
    <form action="<?= $section->link('edit'); ?>" method="post">
      <input type="hidden" name="cmd" value="2">
      <table class="admin_module_form photo_module_edit">
        <tr>
          <td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">родительская страница</td>
          <td>
            <select name="page_id" class="page_select_list">
              <option value="0">не выбрано</option>
              <option disabled="disabled">--страницы--</option>
              <?= $page_list; ?>
            </select>
            <input type="submit" value="сохранить" name="save" class="admin_module_form_submit g-button" />
            <a class="g-button" style="float:right" href="<?= $links['section_index']; ?>">в начало</a>
          </td>
        </tr>
        <tr>
          <td class="admin_module_form_title">название</td>
          <td><input type="text" name="title" value='<?= $section->title; ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">количество на страницу</td>
          <td><input type="text" name="per_page" value='<?= $section->count_per_page; ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">отображение валюты</td>
          <td>
            <select name="currency_id" class="page_select_list">
              <? foreach ( $currency_list as $currency ): ?>
                <option value="<?= $currency->id ?>" <?= ( $currency->id == $section->currency_id ) ? 'selected' : ''; ?> ><?= $currency->title; ?></option>
              <? endforeach; ?>
            </select>
          </td>
        </tr>

        <tr>
            <td class="admin_module_form_title">ширина изображения</td>
            <td><input type="text" name="resize_width" value='<?= $section->resize_width; ?>'/></td>
        </tr>
        <tr>
            <td class="admin_module_form_title">высота изображения</td>
            <td><input type="text" name="resize_height" value='<?= $section->resize_height; ?>'/></td>
        </tr>

        <tr>
          <td class="admin_module_form_title">включить функцию похожих товаров</td>
          <td>
            <input type="checkbox" name="similar_items" value="1" <?= ( $section->similar_items ) ? 'checked' : ''; ?> />
          </td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Пользовательские поля</td>
          <td>
            <? foreach ( $user_fields as $key => $value ): ?>
              <div class="cur_user_field">
                <input type="hidden" name="cur_uf_id[]" value="<?= $value['id']; ?>">
                Название: <input type="text" name="cur_uf_title[]" style="width: 145px;" value="<?= $value['title']; ?>" />
                <?
                switch ($value['type'])
                {
                  case 1:
                    $value = 'целое число';
                    break;
                  case 2:
                    $value = 'вещественное число';
                    break;
                  case 3:
                    $value = 'строка';
                    break;
                  case 4:
                    $value = 'текст';
                    break;
                  case 5:
                    $value = 'дата';
                    break;
                  default:
                    $value = 'none';
                    break;
                }
                ?>
                <input type="text" disabled value="<?= $value; ?>" style="width: 300px;">
              </div>
            <? endforeach; ?>
            <strong>Для удаления поля - оставить название пустым.</strong>
            <br><br>
            <input type="button" class="styler addField" value="Добавить" />
            <input type="button" class="styler delField" value="Удалить" />
            <div class="section"></div>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

<script language="javascript">

  $('input.addField').click(function() {
    $('div.section').append(
      '<div class="user_field">' +
        'Название: <input type="text" name="uf_title[]" style="width: 145px;" />' +
        '<select name="uf_type[]">' +
          '<optgroup label="Тип поля">' +
            '<option value="1">целое число</option>' +
            '<option value="2">вещественное число</option>' +
            '<option value="3">строка</option>' +
            '<option value="4">текст</option>' +
            '<option value="5">дата</option>' +
          '</optgroup>' +
        '</select>' +
      '</div>'
    );
    $('input, select').styler();
  });

  $('input.delField').click(function() {
    var count = $('div.section div.user_field').length;
    if ( count > 0 )
    {
      $('div.section div.user_field:last').remove();
      $('input:file').styler();
    } else {
      alert('Не возможно удалить поле!');
    }
  });

</script>