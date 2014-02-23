<div class="admin_module_title">
    <h4>
        <?= !empty($module_title) ? $module_title : '' ; ?>
    </h4>
</div>

<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= base_url('admin/feedback/add'); ?>">
                <button class="styler">создать форму</button>
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/feedback/history'); ?>">
                <button class="styler">история</button>
            </a>
        </li>
    </ul>
    <ul class="admin_list admin_mainlist">
        <h6>Список форм обратной связи:</h6>
        <? foreach ( $ff_list as $form ) : ?>
            <li>
                <div class="menuline clearfix">
                    <span class="left">
                        <a href="<?= base_url('admin/feedback/edit/'.$form->id); ?>" title="редактировать">
                            <?= $form->title; ?>
                        </a>
                    </span>
                    <span class="right">
                        <a href="<?= base_url('admin/feedback/delete/'.$form->id); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="удалить">
                            <img title="удалить" alt="удалить" src="/www_admin/img/icon_delete_1.5.png" />
                        </a>
                    </span>
                    <span class="right">
                        <a href="<?= base_url('admin/feedback/edit/'.$form->id); ?>" class="admin_form_action_page" title="редактировать">
                            <img title="редактировать" alt="редактировать" src="/www_admin/img/icon_edit_1.5.png"/>
                        </a>
                    </span>
                </div>
            </li>
        <? endforeach; ?>
    </ul>
    <ul class="module_menu">
        <li>
            <a href="#to_top">наверх</a>
        </li>
    </ul>
</div>