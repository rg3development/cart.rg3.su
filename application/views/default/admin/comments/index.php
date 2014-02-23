<div class="admin_module_title">
    <h4>Модуль комментариев</h4>
</div>
<div id="content">
    <? if ( isset($comments['unapproved']) && !empty($comments['unapproved']) ) : ?>
        <div class="comments_module">
            <h5 class="comment_type">Непроверенные комментарии: (<?= count($comments['unapproved']); ?>)</h5>
            <table class="comments_table">
                <thead>
                    <tr>
                        <td class="col1"><strong>Имя</strong></td>
                        <td class="col2"><strong>Email</strong></td>
                        <td class="col3"><strong>Сообщение</strong></td>
                        <td class="col4"><strong>Cтраница</strong></td>
                        <td class="col4"><strong>Ссылка</strong></td>
                        <td class="col5"><strong>Дата</strong></td>
                        <td class="col6"><strong>Проверен</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ( $comments['unapproved'] as $comment) : ?>
                        <tr>
                            <td class="col1">
                                <?= $comment->name; ?>
                            </td>
                            <td class="col2">
                                <a href="mailto:<?= $comment->email; ?>">
                                    <?= $comment->email; ?>
                                </a>
                            </td>
                            <td class="col3">
                                <?= $comment->message; ?>
                            </td>
                            <td class="col4">
                                <?= $comment->title; ?>
                            </td>
                            <td class="col4">
                                <a href="<?= base_url($comment->page_url); ?>" target="_blank">
                                    <?= $comment->page_url; ?>
                                </a>
                            </td>
                            <td class="col5">
                                <?= $comment->date; ?>
                            </td>
                            <td class="col6">
                                <a href="#" id="un_<?= $comment->id; ?>" name="approved_link" data-link="0">
                                    нет
                                </a>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>
    <? if ( isset($comments['approved']) && !empty($comments['approved']) ) : ?>
        <div class="comments_module">
            <h5 class="comment_type">
                Проверенные комментарии: (<?= count($comments['approved']); ?>)
            </h5>
            <table class="comments_table">
                <thead>
                    <tr>
                        <td class="col1"><strong>Имя</strong></td>
                        <td class="col2"><strong>Email</strong></td>
                        <td class="col3"><strong>Сообщение</strong></td>
                        <td class="col4"><strong>Cтраница</strong></td>
                        <td class="col4"><strong>Ссылка</strong></td>
                        <td class="col5"><strong>Дата</strong></td>
                        <td class="col6"><strong>Проверен</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ( $comments['approved'] as $comment) : ?>
                        <tr>
                            <td class="col1">
                                <?= $comment->name; ?>
                            </td>
                            <td class="col2">
                                <a href="mailto:<?= $comment->email; ?>">
                                    <?= $comment->email; ?>
                                </a>
                            </td>
                            <td class="col3">
                                <?= $comment->message; ?>
                            </td>
                            <td class="col4">
                                <?= $comment->title; ?>
                            </td>
                            <td class="col4">
                                <a href="<?= base_url($comment->page_url); ?>" target="_blank">
                                    <?= $comment->page_url; ?>
                                </a>
                            </td>
                            <td class="col5">
                                <?= $comment->date; ?>
                            </td>
                            <td class="col6">
                                <a href="#" id="un_<?= $comment->id; ?>" name="approved_link" data-link="1">
                                    да
                                </a>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>
</div>

<script type="text/javascript">
    $('a[name=approved_link]').click(function () {
        var cur_link  = $(this);
        var data_link = cur_link.attr('data-link');
        var link_id   = cur_link.attr('id');
        var comm_id   = link_id.substr(3);
        if ( data_link == 0 )
        {
            $.ajax({
                url: '/admin/comments/approved/' + comm_id + '/1',
                success: function ( msg ) {
                    console.log('comment [' + comm_id + ']: approved');
                    cur_link.attr('data-link', '1');
                    cur_link.html('да');
                    location.reload();
                }
            });
        } else {
            $.ajax({
                url: '/admin/comments/approved/' + comm_id + '/0',
                success: function ( msg ) {
                    console.log('comment [' + comm_id + ']: unapproved');
                    cur_link.attr('data-link', '0');
                    cur_link.html('нет');
                    location.reload();
                }
            });
        }
        return false;
    });

    $('.comment_type').click(function () {
        $(this).parent().find('table').toggle('slow');
    });

</script>