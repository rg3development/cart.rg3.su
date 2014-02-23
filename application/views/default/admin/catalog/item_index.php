<div class="admin_module_title">
    <h4>
        <?= $module['title']; ?>
    </h4>
</div>
<div id="content">
    <ul class="module_menu">
        <li>
            <a href="<?= base_url($section->link('cat_list')); ?>" title="вернуться в текущий каталог">
                <button class="styler">в каталог</button>
            </a>
        </li>
        <li>
            <a href="<?= $links['section_index']; ?>" title="вернуться к списку каталогов">
                <button class="styler">в начало</button>
            </a>
        </li>
    </ul>
    <ul class="admin_list admin_mainlist">

        <? if ( $is_item ): ?>
            <h6>Список категорий товара: <em class="sub_title"><?= $item->title; ?></em></h6>
            <? if ( empty($categories) ) : ?>
                <h5>Список категорий пуст! К данному товару не привязано ни одной категории.</h5>
            <? endif; ?>
            <? foreach ( $categories as $category ): ?>
                <li>
                    <div class="menuline clearfix">
                        <span class="left">
                            <a href="<?= $category->link('items'); ?>" title="просмотреть список товаров в категории">
                                <?= $category->title; ?>
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= $item->link('unlink', $category->id); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="Отвязать от категории">
                                <img title="Отвязать от категории" src="/www_admin/img/icon_copy_1.5.png" />
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= $category->link('del'); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены? Будут удалены все категории в данном каталоге!')) return true; else return false;" title="удалить">
                                <img title="удалить" alt="удалить" src="/www_admin/img/icon_delete_1.5.png" />
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= $category->link('edit'); ?>" class="admin_form_action_page" title="редактировать">
                                <img title="редактировать" alt="редактировать" src="/www_admin/img/icon_edit_1.5.png"/>
                            </a>
                        </span>
                        <span class="right catalogs" title="кол-во товаров">
                            Товары: <?= $category->num_products(); ?>
                        </span>
                        <span class="right priority" title="порядок">
                            <?= $category->priority; ?>
                        </span>
                    </div>
                </li>
            <? endforeach; ?>
        <? else: ?>
            <h6>Список товаров категории: <em class="sub_title"><?= $category->title; ?></em></h6>
            <? if ( empty($items) ) : ?>
                <h5>Список товаров пуст! Данная категория не связана ни с одним товаром.</h5>
            <? endif; ?>
            <? foreach ( $items as $item ): ?>
                <li>
                    <div class="menuline clearfix">
                        <span class="left">
                            <a href="<?= $item->link('item_list'); ?>" title="посмотреть список категорий товара">
                                <?= $item->title; ?>
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= $category->link('unlink', $item->id); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены?')) return true; else return false;" title="Отвязать товар">
                                <img title="Отвязать товар" src="/www_admin/img/icon_copy_1.5.png" />
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= $item->link('del'); ?>" class="admin_form_action_page" onclick="if (confirm('Вы уверены? Будут удалены все категории в данном каталоге!')) return true; else return false;" title="удалить">
                                <img title="удалить" alt="удалить" src="/www_admin/img/icon_delete_1.5.png" />
                            </a>
                        </span>
                        <span class="right">
                            <a href="<?= $item->link('edit'); ?>" class="admin_form_action_page" title="редактировать">
                                <img title="редактировать" alt="редактировать" src="/www_admin/img/icon_edit_1.5.png"/>
                            </a>
                        </span>
                        <span class="right catalogs" title="кол-во категорий">
                            Категории: <?= $item->num_categories(); ?>
                        </span>
                    </div>
                </li>
            <? endforeach; ?>
        <? endif; ?>

    </ul>
    <ul class="module_menu">
        <li>
            <a href="#to_top">наверх</a>
        </li>
    </ul>
</div>