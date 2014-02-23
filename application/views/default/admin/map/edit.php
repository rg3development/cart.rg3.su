    <div class="admin_module_title"><h4><?=!empty($module_title) ? $module_title : '';?></h4></div>
    <div id="content">
        <form action="<?=base_url();?>admin/map/edit/<?=$data['id'];?>" method="post" enctype="multipart/form-data">
                <table class="admin_module_form">
                    <tr><td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td></tr>
                    <tr><td class="admin_module_form_title">название страницы</td><td><input type="text" name="title" value="<?=$data['title'];?>" /></td></tr>
                    <tr><td class="admin_module_form_title">показывать название</td><td><input type="checkbox" name="show_title" class="admin_module_form_checkbox styled" <?=!empty($data['show_title']) && $data['show_title'] == '1' ? 'checked' : '';?> /></td></tr>
                    <tr>
                        <td class="admin_module_form_title">псевдоним страницы</td>
                        <td><input type="text" name="alias" value="<?=$data['alias'];?>" /></td>
                    </tr>
                    <tr>
                        <td class="admin_module_form_title">показывать псевдоним</td>
                        <td><input type="checkbox" name="show_alias" class="admin_module_form_checkbox styled" <?=!empty($data['show_alias']) && $data['show_alias'] == '1' ? 'checked' : '';?> /></td>
                    </tr>
                    <tr><td class="admin_module_form_title">url</td><td><input type="text" name="url" value="<?=$data['url'];?>" /></td></tr>
                    <tr>
                        <td class="admin_module_form_title">порядок</td>
                        <td>
                            <input type="text" name="priority" value='<?= $data['priority']; ?>' />
                        </td>
                    </tr>
                    <tr><td class="admin_module_form_title">ключевые слова</td><td><input type="text" name="keywords" value='<?=$data['keywords'];?>'/></td></tr>
                    <tr><td class="admin_module_form_title">описание</td><td><input type="text" name="description" value='<?=$data['description'];?>'/></td></tr>
                    <tr>
                        <td class="admin_module_form_title">родительская страница</td>
                        <td>
                            <select name="parent_id" class="page_select_list">
                                <option value="0">не выбрано</option>
                                <?=$page_select;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="admin_module_form_title">шаблон</td>
                        <td>
                            <select name="template" class="page_select_list">
                                <? foreach ( $page_layouts as $tpl_name => $tpl_title ) : ?>
                                    <option value="<?= $tpl_name; ?>" <?= ( $data['template'] == $tpl_name ) ? 'selected' : '' ; ?>>
                                        <?= $tpl_title; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td class="admin_module_form_title">показывать</td><td><input type="checkbox" name="show" class="admin_module_form_checkbox styled" <?=!empty($data['show']) && $data['show'] == '1' ? 'checked' : '';?> /></td></tr>
                    <tr>
                        <td class="admin_module_form_title">модули</td>
                        <td class="admin_module_form_table_tiltle">
                            <table class="modules">
                                <tr>
                                    <td class="modules_title">доступные модули</td>
                                    <td class="modules_title">модули добавленные на страницу</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="admin_module_form_title"></td>
                        <td>
                            <ul id="sortable1" class="admin_module_form_connected_sortable">
                                <?php foreach ($list_modules as $module) : ?>
                                <li>
                                    <input type="hidden" class="admin_module_map_module" name="" value="<?=$module['id'];?>" />
                                    <?=$module['title'];?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <ul id="sortable2" class="admin_module_form_connected_sortable">
                                <?php foreach ($active_modules as $module) : ?>
                                <li>
                                    <input type="hidden" class="admin_module_map_module" name="" value="<?=$module['id'];?>" />
                                    <?=$module['title'];?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="admin_module_form_title"></td>
                        <td class="admin_td_submit">
                            <input type="submit" value="Сохранить" class="admin_module_form_submit g-button" onclick="$('#sortable1').find('input').attr('name', ''); $('#sortable2').find('input').attr('name', 'active_form[]');"/>
                        </td>
                    </tr>
                </table>
        </form>
    </div>
    <style>
    #sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; }
    #sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 120px; }
    </style>
    <script>
    $(function() {
        $("#sortable1, #sortable2").sortable({
            connectWith: ".admin_module_form_connected_sortable",
            opacity: 0.6,
        }).disableSelection();
    });
    </script>
