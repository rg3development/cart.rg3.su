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
  <h4 id="comments" class="titleBold">
    <?= isset($approved_message) ? $approved_message : ''; ?>
  </h4>
</section>