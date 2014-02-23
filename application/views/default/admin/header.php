<?php
    Header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");
    Header("Cache-Control: no-cache, must-revalidate");
    Header("Pragma: no-cache");
    Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8;charset=utf-8" />
    <title>RG3 Development. Панель администрирования сайтом.</title>
    <? if (!empty($css)) :?>
    <? foreach ($css as $css_item) :?>
        <link rel="stylesheet" href="<?=$css_item;?>" type="text/css" media="screen" />
    <? endforeach;?>
    <? endif;?>
    <? if (!empty($scripts)) :?>
    <? foreach ($scripts as $script) :?>
        <script type="text/javascript" src="<?=$script?>"></script>
    <? endforeach;?>
    <? endif;?>

    <script>
        (function($) {
            $(function() {
                $('input, select').styler();
            })
        })(jQuery)
    </script>

</head>
<body id="to_top">
    <div id="header">
    <div id="nav-slider">
        <div class="inner">
            <ul>
                <? foreach ( $menu as $href => $title ) : ?>
                    <li>
                        <a href="<?= base_url($href); ?>">
                            <?= $title; ?>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
