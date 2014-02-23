<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?=$page_info['title'];?> - <?=$site_settings['title'];?></title>
    <meta name="keywords" content="<?=!empty($page_info['keywords']) ? $page_info['keywords'] : $site_settings['keywords'];?>">
    <meta name="description" content="<?=!empty($page_info['description']) ? $page_info['description'] : $site_settings['description'];?>">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="/css/site/base.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/js/plugins/lightbox/css/jquery.lightbox-0.5.css" type="text/css"/>
    <script type="text/javascript" src="/js/plugins/jquery.js"></script>
    <script type="text/javascript" src="/js/plugins/lightbox/js/jquery.lightbox-0.5.pack.js"></script>
    <script type="text/javascript" src="/js/plugins/slides.min.jquery.js"></script>
    <script type="text/javascript" src="/js/site/script.js"></script>
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

</head>
<div id="wrap">
    <header class="header">
        <div class="marker"></div>
        <div class="banner-top"><img src="/img/site/animation.gif" alt=""></div>
        <div id="logo"><a href="/"><?=$site_settings['logo'] ? "<img class='logo' src={$site_settings['logo']} />" : '';?></a></div>
        <nav class="menu">
            <ul>
                <?=$menu;?>
            </ul>
        </nav>
        <div class="auth-link"><a href="#">Вход в кабинет</a></div>
    </header>
    <div class="content">