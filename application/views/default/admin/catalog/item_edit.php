<input type="hidden" value="<?= $item->id; ?>" name="item_id" />
<div class="admin_module_title">
  <h4><?= $module['title']; ?></h4>
</div>
<div id="content">
  <div class="text_module">
    <form action="<?= $item->link('edit'); ?>" method="post" enctype="multipart/form-data">
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
            <select multiple="multiple" name="parent_category_id[]" style="width: 400px;">
              <option value="0">не выбрано</option>
              <optgroup label="категории">
                <? foreach ( $category_list as $category ): ?>
                  <option value="<?= $category->id; ?>" id="<?= $category->id; ?>" <?= in_array($category->id, $item_links) ? 'selected' : ''; ?>>
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
            <div class="img_section clearfix">
              <? foreach ( $item_images as $image ): ?>
                <input type="hidden" name="cur_ids[]" value="<?= $image['info']['id']; ?>">
                <div class="image-wrap">
                  <div class="image-main">
                    <input type="radio" name="is_main" value="<?= $image['info']['id']; ?>" <?= ( $image['info']['is_main'] ) ? 'checked' : ''; ?> /> Основное
                  </div>
                  <div class="image-item" style="background-image: url(<?= '/upload/images/catalog/' . $image['img']->getFilenameThumb(); ?>)"></div>
                  <div class="image-priority">
                    <input type="text" name="priority[]" placeholder="Приоритет" value="<?= $image['info']['priority']; ?>" style="width: 85px;" />
                  </div>
                  <p class="image-delete">
                    <a href="/admin/catalog/imgdel/<?= $item->id ?>/<?= $image['img']->getId(); ?>" onclick="if (confirm('Вы уверены?')) return true; else return false;">
                      удалить
                    </a>
                  </p>
                </div>
              <? endforeach; ?>
            </div>
            <div class="clearfix">
              <input type="button" class="styler addImages" value="Добавить" />
              <input type="button" class="styler delImages" value="Удалить" />
              <input type="hidden" value="1" id="img_count" name="img_count">
            </div>
            <div class="section clearfix">
              <div class="image_add">
                <input type="file" name="image_1" />
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Название</td>
          <td><input class="styler" type="text" name="title" value='<?= $item->title; ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Артикул</td>
          <td><input class="styler" type="text" name="article" value='<?= $item->article; ?>' /></td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Цена</td>
          <td><input class="styler" type="text" name="price" value='<?= $item->price; ?>' /></td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Товар в наличии</td>
          <td><input type="checkbox" name="in_stock" <?= ( $item->in_stock == '1' ) ? 'checked' : ''; ?> /></td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Бестселлер (ТОП-продаж)</td>
          <td>
            <input type="checkbox" name="is_bestseller" <?= ( $item->is_bestseller == '1' ) ? 'checked' : ''; ?> />
          </td>
        </tr>
        <tr>
          <td class="admin_module_form_title">Распродажа</td>
          <td>
            <input type="checkbox" name="is_sale" <?= ( $item->is_sale == '1' ) ? 'checked' : ''; ?> />
          </td>
        </tr>

        <tr>
          <td class="admin_module_form_title">Порядок</td>
          <td><input class="styler" type="text" name="item_priority" value='<?= $item->priority; ?>' /></td>
        </tr>

        <? if ( $section->similar_items ) : ?>
          <tr>
            <td class="admin_module_form_title">Схожие товары</td>
            <td class="">
              <div>
                <select name="sim_sections" id="">
                  <option value="-1" selected disabled>Выбирите раздел</option>
                  <? foreach ( $section_list as $key => $value ) : ?>
                    <option value="sec<?= $value->id ?>"><?= $value->title; ?></option>
                  <? endforeach; ?>
                </select>
                <br /><br />
              </div>
              <div name="sim_add">
                <a href="#">Добавить</a>
              </div>
              <div name="sim_list">
                <? if ( isset($similar_items) && !empty($similar_items) ) : ?>
                  <? foreach ( $similar_items as $key => $value ) : ?>
                    <p>
                      <?= $value->title; ?>
                      <a href="/admin/catalog/unset_similar/<?= $item->id; ?>/<?= $value->id; ?>" onclick="if (confirm('Вы уверены?')) return true; else return false;">(удалить)</a>
                    </p>
                  <? endforeach; ?>
                <? endif; ?>
              </div>
            </td>
          </tr>
        <? endif; ?>

        <tr>
          <td class="admin_module_form_title">Описание</td>
          <td>
            <textarea class="ckeditor" id="description" name="description"><?= $item->description; ?></textarea>
          </td>
        </tr>
        <? if ( isset($user_values) && !empty($user_values) ): ?>
          <input type="hidden" name="form_type" value="0">
          <? foreach ($user_values as $key => $value): ?>
            <tr>
              <input type="hidden" value="<?= $value['user_field_id']; ?>" name="uf_ids[]">
              <input type="hidden" value="<?= $value['type']; ?>" name="uf_types[]">
              <td class="admin_module_form_title"><?= $value['title']; ?></td>
              <td>
                <?
                switch ( $value['type'] )
                {
                  case 1:
                    $f_value = $value['value_int'];
                    break;
                  case 2:
                    $f_value = $value['value_float'];
                    break;
                  case 3:
                    $f_value = $value['value_string'];
                    break;
                  case 4:
                    $f_value = $value['value_text'];
                    break;
                  case 5:
                    if ( $value['value_date'] )
                    {
                      $f_value = date('Y-m-d', strtotime($value['value_date']));
                    } else {
                      $f_value = 'NULL';
                    }
                    break;
                  default:
                    $f_value = '';
                    break;
                }
                ?>
                <input type="text" name="uf_values[]" value="<?= ( $f_value == 'NULL' ) ? '' : $f_value; ?>">
              </td>
            </tr>
          <? endforeach; ?>
        <? elseif ( isset($user_fields) ): ?>
          <input type="hidden" name="form_type" value="1">
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

  $.fn.toggleDisabled = function() {
    return this.each(function() {
      this.disabled = !this.disabled;
    });
  };

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

  $('select[name=sim_sections]').change( function () {
    if ( $('select[name=sim_items]').length > 0 )
    {
      $('select[name=sim_items] option')[0].selected = true;
      $('select[name=sim_items]').toggleDisabled();
      $('select[name=sim_items]').trigger('refresh');
    }
    var section_value = $(this).attr('value');
    var section_id = section_value.substr(3);
    $.ajax({
      url: '/admin/catalog/get_categories/' + section_id,
      dataType: 'json',
      success: function ( categories ) {
        var options = '<option value="-1" selected disabled>Выбирите категорию</option>';
        options += '<option value="cat0">Товары без категории</option>';
        for ( var i = 0; i < categories.length; i++ ) {
          options += '<option value="cat' + categories[i].id + '">' + categories[i].title + '</option>';
        }
        var categorySelect = $('select[name=sim_categories]');
        if ( categorySelect.length == 0 )
        {
          $('select[name=sim_sections]').parent().append('<select name="sim_categories"></select><br /><br />');
          $('select[name=sim_categories]').styler();
        }
        $('select[name=sim_categories]').html(options);
        $('select[name=sim_categories]').trigger('refresh');
      }
    });
  });

  $('select[name=sim_categories]').live('change', function () {
    var category_value = $(this).attr('value');
    var category_id = category_value.substr(3);
    if ( category_id == 0 )
    {
      category_id = '0/' + $('select[name=sim_sections]').attr('value').substr(3);
    }
    $.ajax({
      url: '/admin/catalog/get_items/' + category_id,
      dataType: 'json',
      success: function ( items ) {
        var options = '<option value="-1" selected disabled>Выбирите товар</option>';
        for ( var i = 0; i < items.length; i++ ) {
          options += '<option value="item' + items[i].id + '">' + items[i].title + '</option>';
        }
        if ( $('select[name=sim_items]').length > 0 )
        {
          $('select[name=sim_items]').removeAttr('disabled');
        }
        var itemSelect = $('select[name=sim_items]');
        if ( itemSelect.length == 0 )
        {
          $('select[name=sim_sections]').parent().append('<select name="sim_items"></select><br /><br />');
          $('select[name=sim_items]').styler();
        }
        $('select[name=sim_items]').html(options);
        $('select[name=sim_items]').trigger('refresh');
      }
    });
  });

  $('div[name=sim_add] a').click( function () {
    var sim_item_id = 0;
    var item_id = $('input[name=item_id]').val();

    var itemSelect = $('select[name=sim_items]');
    if ( itemSelect.length > 0 )
    {
      sim_item_id = $('select[name=sim_items]').val().substr(4);
    }
    if ( item_id && sim_item_id )
    {
      console.log(item_id + '/' + sim_item_id);
      $.ajax({
        type: 'POST',
        url: '/admin/catalog/set_similar',
        data: { 'item_id': item_id, 'sim_item_id': sim_item_id },
        dataType: 'json'
      }).done( function( response ) {
        alert(response.msg);
        if ( response.code == 1 )
        {
          var item = $('select[name=sim_items] option:selected').text();
          var sim_list = $('div[name=sim_list]');
          sim_list.append('<p>Товар добавлен: ' + item + '</p>');
        }
      });
    } else {
      alert('Необходимо выбрать товар!');
    }
    return false;
  });

</script>