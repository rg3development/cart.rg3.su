<section id="comment">
  <? if ( isset($comments) && !empty($comments) ): ?>
    <h4 class="titleBold">
      Комментарии: <?= count($comments); ?>
    </h4>
    <ol class="commentlist">
      <? foreach ( $comments as $key => $value ) : ?>
        <li>
          <div class="comment-body">
            <cite class="fn">
              <a href="mailto:<?= $value->email; ?>"><?= $value->name; ?></a>
            </cite>
            <span class="tdate"><?= $value->date; ?></span>
            <div class="clear"></div>
            <div class="commenttext">
              <p><?= $value->message; ?></p>
            </div>
          </div>
            <div class="clear"></div>
        </li>
      <? endforeach; ?>
    </ol>
  <? endif; ?>
  <h4 class="titleBold">Оставить комментарий</h4>
  <form id="comments" action="<?= $_SERVER['REQUEST_URI']. '#comments'; ?>" method="post">
    <fieldset>
        <div>
            <label for="name" id="name_label">Имя:</label>
            <input type="text" name="name" id="name" size="50" class="text-input <?=form_error('name') ? 'error_box' : ''?>" value='<?=set_value('name');?>'>
        </div>
        <div>
            <label for="email" id="email_label">Email:</label>
            <input type="text" name="email" id="email" size="50" class="text-input <?=form_error('email') ? 'error_box' : ''?>" value='<?=set_value('email');?>'>
        </div>
        <div>
            <label for="msg" id="msg_label">Сообщение:</label>
            <textarea cols="10" rows="7" name="message" id="msg" class="text-input <?=form_error('message') ? 'error_box' : ''?>" ><?=set_value('message');?></textarea><br />
        </div>
        <div>
          <input type="submit" name="submit" class="button" id="submit_btn" value="Отправить"/><br class="clear" />
          <? if ( validation_errors() ) : ?>
            <p class="error_text">
              <?=validation_errors();?>
            </p>
          <? endif; ?>
        </div>
    </fieldset>
  </form>
</section>