<div class="admin_module_title">
  <h4><?= $module['title']; ?></h4>
</div>
<div id="content">
  <div class="text_module">
    <form action="<?= $section->link('item_add'); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="cmd" value="1">
      <input type="hidden" name="parent_section_id" value="<?= $section->id; ?>">
      <table class="admin_module_form photo_module_edit">
        <tr>
          <td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Каталог</td>
          <td>
            <input name="section_title" class="styler" type="text" disabled="disabled" value="<?= $section->title; ?>">
          </td>
        </tr>
        <tr>
          <td class="admin_module_form_title">категория товара</td>
          <td>
            <select multiple="multiple" name="parent_category_id[]">
              <option value="0">не выбрано</option>
              <optgroup label="категории">
                <? foreach ( $category_list as $category ): ?>
                  <option value="<?= $category->id; ?>" id="<?= $category->id; ?>">
                    <?= $category->title; ?>
                  </option>
                <? endforeach; ?>
              </optgroup>
            </select>
            Добавить несколько категорий через Ctrl.
            <input type="submit" value="сохранить" name="save" class="admin_module_form_submit styler" />
            <a class="g-button" style="float:right" href="<?= $links['section_index']; ?>">в начало</a>
          </td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Фотографии товара</td>
          <td>
            <input type="button" class="styler addImages" value="Добавить" />
            <input type="button" class="styler delImages" value="Удалить" />
            <input type="hidden" value="1" id="img_count" name="img_count">
            <div class="section">
              <div class="image_add">
                <input type="file" name="image_1" />
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Название</td>
          <td><input class="styler" type="text" name="title" value='<?= set_value('title'); ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Артикул</td>
          <td><input class="styler" type="text" name="article" value='<?= set_value('article'); ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Цена</td>
          <td><input class="styler" type="text" name="price" value='<?= set_value('price'); ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Товар в наличии</td>
          <td>
            <input type="checkbox" name="in_stock" />
          </td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Бестселлер (ТОП-продаж)</td>
          <td>
            <input type="checkbox" name="is_bestseller" />
          </td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Распродажа</td>
          <td>
            <input type="checkbox" name="is_sale" />
          </td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Порядок</td>
          <td><input class="styler" type="text" name="item_priority" value='<?= set_value('item_priority'); ?>' /></td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Описание</td>
          <td>
            <textarea class="ckeditor" id="description" name="description"><?= set_value('description'); ?></textarea>
          </td>
        </tr>
        <tr>
          <td class="admin_module_form_title"><strong>Пользовательский поля</strong></td>
          <td></td>
        </tr>
        <? if ( isset($user_fields) ): ?>
          <? foreach ($user_fields as $key => $value): ?>
            <tr>
              <input type="hidden" value="<?= $value['id']; ?>" name="uf_ids[]">
              <input type="hidden" value="<?= $value['type']; ?>" name="uf_types[]">
              <td class="admin_module_form_title"><?= $value['title']; ?></td>
              <td>
                <input type="text" value="" name="uf_values[]">
              </td>
            </tr>
          <? endforeach; ?>
        <? endif; ?>
      </table>
    </form>
  </div>
</div>

<script language="javascript">

  $('input.addImages').click(function() {
    var cur_count = $('#img_count').val();
    var img_count = parseInt(cur_count) + 1;
    $('#img_count').val(img_count);

    $('div.section').append('<div class="image_add"><input type="file" name="image_' + img_count + '"></div>');
    $('input:file').styler();
  });

  $('input.delImages').click(function() {
    var count = $('div.section div.image_add').length;
    if ( count > 1 )
    {
      var cur_count = $('#img_count').val();
      var img_count = parseInt(cur_count) - 1;
      $('#img_count').val(img_count);

      $('div.section div.image_add:last').remove();
      $('input:file').styler();
    } else {
      alert('Не возможно удалить изображение!');
    }
  });

</script>