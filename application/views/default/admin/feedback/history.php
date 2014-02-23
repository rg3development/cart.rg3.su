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
            <a href="<?= base_url('admin/feedback'); ?>">
                <button class="styler">к списку ФОС</button>
            </a>
        </li>
    </ul>
    <ul class="admin_list admin_mainlist">
        <h6>История отправки сообщений:</h6>
        <table class="feedback_history" cellspacing="4">
            <tr>
                <td class="history1">№</td>
                <td class="history2">Дата</td>
                <td class="history3">Название формы</td>
                <td class="history4">Тема письма</td>
                <td class="history5">Кому</td>
                <td class="history6">Сообщение</td>
            </tr>
            <? foreach ( $history as $key => $element ) : ?>
                <tr>
                    <td class="history1">
                        <?= ($key + 1); ?>
                    </td>
                    <td class="history2">
                        <?= $element->send_date; ?>
                    </td>
                    <td class="history3">
                        <?= $element->form_title; ?>
                    </td>
                    <td class="history4">
                        <?= $element->email_subject; ?>
                    </td>
                    <td class="history5">
                        <?= $element->email_to; ?>
                    </td>
                    <td class="history6">
                        <?= nl2br($element->message); ?>
                    </td>
                </tr>
            <? endforeach; ?>
        </table>
    </ul>
    <ul class="module_menu">
        <li>
            <a href="#to_top">наверх</a>
        </li>
    </ul>
</div>