<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="/www_site/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/www_site/css/cssreset.css" />
        <meta name="keywords" content="<?=!empty($page_info['keywords']) ? $page_info['keywords'] : $site_settings['keywords'];?>">
        <meta name="description" content="<?=!empty($page_info['description']) ? $page_info['description'] : $site_settings['description'];?>">
        <title><?=$site_settings['title'];?> - ГЛАВНАЯ</title>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

        <script type='text/javascript' src='/www_site/js/jquery.cycle.all.min.js'></script>
        <script type='text/javascript' src='/www_site/js/presentationCycle.js'></script>

        <!-- This loads the YouTube IFrame Player API code -->
        <script src="http://www.youtube.com/player_api"></script>

        <!-- Add fancyBox -->
        <link rel="stylesheet" href="/www_site/js/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
        <script type="text/javascript" src="/www_site/js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
    </head>
    <body>
        <div  class="wrap">

            <div class="header">
                <a href="http://www.erichkrause.ru/" target="_blank" class="logo"></a>
                <h1>ТЕХНОЛОГИЯ ULTRA GLIDE<br>ПОЛНЫЙ ВОСТОРГ!</h1>
                <a href="/" class="logo2"></a>
                <div class="menu">
                    <ul>
                        <li><a class="m3" href="/">главная</a></li>
                        <li><a class="m1" href="/about">о продукте</a></li>
                        <li class="none"><a class="m2" href="/shops">магазины</a></li>
                    </ul>
                </div>
            </div>

            <div class="banner">
                <div id="presentation_container" class="pc_container">
                    <div class="pc_item">
                          <img src="/www_site/img/slide0_new.png" alt="" />
                    </div>
                    <div class="pc_item">
                          <img src="/www_site/img/slide5.png" alt="U18" />
                    </div>
                    <div class="pc_item">
                          <img src="/www_site/img/slide1.png" alt="U18" />
                    </div>
                    <div class="pc_item">
                          <img src="/www_site/img/slide2.png" alt="U19" />
                    </div>
                    <div class="pc_item">
                          <img src="/www_site/img/slide3.png" alt="U28" />
                    </div>
                    <div class="pc_item">
                          <img src="/www_site/img/slide4.png" alt="U29" />
                    </div>
                </div>
                <script type="text/javascript">
                    presentationCycle.init();
                </script>
            </div>