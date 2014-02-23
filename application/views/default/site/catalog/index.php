<?
// цикл получения всех изображений товара. доступно внутри цикла
// foreach ( $catalog_products as $index => $product )
/*
  <? foreach ( $product->images() as $product_image ) : ?>
    <img src="<?= $product->img_src($product_image); ?>" />
  <? endforeach; ?>
*/
/*
  $item->description()    = полное описание товара (с форматрованием)
  $item->description(100) = краткое описание товара (без форматрованием)
*/
/*
  $item->in_stock()       = строковое представление поля "Товар в наличии"
  $item->in_stock(TRUE)   = булево представление поля "Товар в наличии"
*/
?>

<div class="padcontent">
  <div id="ts-display-products">
    <ul class="ts-display-pd-col-3">
      <? if ( isset($catalog_products) && !empty($catalog_products) ) : ?>
        <? foreach ( $catalog_products as $index => $product ) : ?>
          <li class="<?= ( (($index + 1) % 3 === 0) ) ? 'nomargin' : ''; ?>">
            <a href="<?= $product->url(); ?>">
              <img src="<?= $product->thumb(); ?>" alt="" class="scale-with-grid imgborder" />
            </a>
            <h2>
              <a href="<?= $product->url(); ?>">
                <?= $product->title; ?>
              </a>
            </h2>
            <div class="price">
              <?= $product->price(); ?>
            </div>
          </li>
        <? endforeach; ?>
      <? endif; ?>
    </ul>
   </div>
   <div class="clear"></div>
   <?= ! empty($paginator) ? $paginator : ''; ?>
</div>