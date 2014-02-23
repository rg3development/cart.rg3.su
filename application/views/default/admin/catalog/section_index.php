<style type="text/css">
    .set_table {
        padding: 0px 0px 0px 80px;
        margin: 0px auto 0px auto;
    }
    .set_table input[type="text"] {
        width: 400px;
    }
    .set_title {
        width: 230px !important;
        padding-bottom: 10px;
    }
    .set_form {
        margin: 0 auto;
    }
</style>

<div class="admin_module_title">
    <h4>
        <?= $module['title']; ?>
    </h4>
</div>

<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= $links['section_add']; ?>">
                <button class="styler">создать каталог</button>
            </a>
        </li>
    </ul>

    <form action="/admin/catalog/index" method="post" class="set_form">
        <table class="set_table">
            <tr>
                <td class="admin_module_form_title"></td><td class="admin_error_message"><?=validation_errors();?></td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо клиенту - от кого (почта)</td>
                <td>
                    <input type="text" name="cart_client_sender_email" value='<?= $cart_settings['CART_CLIENT_SENDER_EMAIL']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо клиенту - от кого (имя)</td>
                <td>
                    <input type="text" name="cart_client_sender_name" value='<?= $cart_settings['CART_CLIENT_SENDER_NAME']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо клиенту - тема письма</td>
                <td>
                    <input type="text" name="cart_client_subject" value='<?= $cart_settings['CART_CLIENT_SUBJECT']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо клиенту - сообщение в письме</td>
                <td>
                    <input type="text" name="cart_client_message" value='<?= $cart_settings['CART_CLIENT_MESSAGE']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо оператору - от кого (почта)</td>
                <td>
                    <input type="text" name="cart_operator_sender_email" value='<?= $cart_settings['CART_OPERATOR_SENDER_EMAIL']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо оператору - от кого (имя)</td>
                <td>
                    <input type="text" name="cart_operator_sender_name" value='<?= $cart_settings['CART_OPERATOR_SENDER_NAME']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо оператору - тема письма</td>
                <td>
                    <input type="text" name="cart_operator_subject" value='<?= $cart_settings['CART_OPERATOR_SUBJECT']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо оператору - почта оператора</td>
                <td>
                    <input type="text" name="cart_operator_email" value='<?= $cart_settings['CART_OPERATOR_EMAIL']; ?>' />
                </td>
            </tr>
            <tr>
                <td class="admin_module_form_title set_title">письмо оператору - сообщение в письме</td>
                <td>
                    <input type="text" name="cart_operator_message" value='<?= $cart_settings['CART_OPERATOR_MESSAGE']; ?>' />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="сохранить настройки" name="save" class="g-button" style="float: right;" />
                </td>
            </tr>
        </table>
    </form>

    <ul class="admin_list admin_mainlist">
        <h6>Список каталогов:</h6>
        <? foreach ( $catalog_section_list as $section ): ?>
            <li>
                <div class="menuline clearfix">
                    <span class="left">
                        <a href="<?= $section->link('cat_list'); ?>" title="просмотр каталога">
                            <?= $section->title; ?>
                        </a>
                    </span>
                    <span class="right">
                        <a href="<?= $section->link('del'); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены? Будут удалены все категории в данном каталоге!')) return true; else return false;" title="удалить">
                            <img title="удалить" alt="удалить" src="/www_admin/img/icon_delete_1.5.png" />
                        </a>
                    </span>
                    <span class="right">
                        <a href="<?= $section->link('edit'); ?>" class="admin_form_action_page" title="редактировать">
                            <img title="редактировать" alt="редактировать" src="/www_admin/img/icon_edit_1.5.png"/>
                        </a>
                    </span>
                    <span class="right catalogs" title="кол-во товаров">
                        Товары: <?=  $section->num_products(); ?>
                    </span>
                    <span class="right catalogs" title="кол-во категорий">
                        Категории: <?= $section->num_categories; ?>
                    </span>
                    <span class="right url" title="url страницы">
                        ( <?= $section->page_url(); ?> )
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



