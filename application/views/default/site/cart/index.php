<div id="cart">
  <table>
    <thead>
      <tr>
        <th class="remove"></th>
        <th class="product">Товар</th>
        <th class="desc">Описание товара</th>
        <th class="unit-price">Цена</th>
        <th class="qty">Количество</th>
        <th class="total">Сумма</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td class="rounded-foot" colspan="7">
          <a class="checkout-button button alt" href="#" onclick='document.forms["order"].submit();'>Обновить корзину</a>
          <a class="checkout-button button alt" href="#" onclick='document.forms["checkout"].submit();'>Оформить заказ</a>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <form action="/catalog" method="post" name="order">
        <input type="hidden" name="type" value="upd">
        <? foreach ( $this->cart->contents() as $index => $item ): ?>
          <? $cur_item = $this->catalog_mapper->get_object($item['id'], 'item'); ?>

          <tr <?= ( ($index + 1) % 2 == 0 ) ? 'class="even"' : '' ; ?>>
            <td class="remove">
              <a class="remove" title="Удалить из корзины" href="#" onclick='document.forms["delete<?= $item['id']; ?>"].submit();'>×</a>

            </td>
            <td class="product">
              <a href="<?= $cur_item->url(); ?>">
                <img style="width: 71px;" src="<?= $cur_item->image(); ?>" alt="" />
              </a>
            </td>
            <td class="desc">
              <a href="<?= $cur_item->url(); ?>">
                <?= $cur_item->title; ?>
              </a>
            </td>
            <td class="unit-price">
              <?= $cur_item->price(); ?>
            </td>
            <td>
              <div class="quantity buttons_added">
                <input type="button" class="minus dec-qty" value="-">
                <input type="hidden" name="my_ids[]" value="<?= $item['rowid']; ?>">
                <input maxlength="12" class="input-text qty text" size="4" value="<?= $item['qty']; ?>" name="my_qty[]">
                <input type="button" class="plus inc-qty" value="+">
              </div>
            </td>
            <td>
              <?= $this->cart->format_number($item['subtotal']); ?>
            </td>
          </tr>
        <? endforeach; ?>
      </form>
      <? foreach ( $this->cart->contents() as $index => $item ): ?>
        <form name="delete<?=$item['id'];?>" action="/catalog" method="post">
          <input type="hidden" name="rowid" value="<?= $item['rowid']; ?>">
          <input type="hidden" name="type" value="del">
        </form>
      <? endforeach; ?>
      <form name="checkout" method="post">
        <input type="hidden" name="type" value="order">
      </form>
    </tbody>
  </table>
</div>

<br><br>

<div class="cart_totals">
  <h2>Сумма заказа</h2>
  <table cellspacing="0" cellpadding="0">
    <tbody>
      <tr class="cart-subtotal">
        <th>Итого :</th>
        <td>
          <?= $this->cart->format_number($this->cart->total()); ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<div class="clear"></div><!-- clear float -->

<script type="text/javascript">
  $(document).ready(function() {

    $('.inc-qty').click(function() {
      var cur_input = $(this).parent().find('input.qty');
      var cur_value = cur_input.val();
      cur_input.attr('value', parseInt(cur_value) + 1);
      return false;
    });

    $('.dec-qty').click(function() {
      var cur_input = $(this).parent().find('input.qty');
      var cur_value = cur_input.val();
      cur_input.attr('value', Math.max(0, parseInt(cur_value) - 1));
      return false;
    });

  });
</script>