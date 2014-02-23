<?
header('Content-Type: text/xml; charset=utf-8');
print('<?xml version="1.0" encoding="utf-8"?>' . "\n");
?>

<rss version="2.0">
    <channel>
    <?php if ($news_category->show_title) :?>
    <title><?=$news_category->title;?></title>
    <?php else: ?>
    <title>Новости RSS канал</title>
    <?php endif; ?>
    <link><?=base_url();?></link>
    <description>RSS Канал с сайта <?=SITE_NAME;?></description>
    <language>ru-ru</language>
    <pubDate><?=date("r");?></pubDate>
    <lastBuildDate><?=date("r");?></lastBuildDate>
    <webMaster>info@rg3.su</webMaster>
    <?php if (sizeof($news_list) > 0) :?>
        <?php foreach ($news_list as $key => $news) :?>
        <item>
            <title><?=$news->title;?></title>
            <link><?=base_url() . $page_urls[$key].'?news_id='.$news->id;?></link>
            <description><?=strip_tags($news->anno);?></description>
            <pubDate><?=date("d/m", $news->created);?></pubDate>
        </item>
        <?php endforeach; ?>
    <?php endif; ?>
    </channel>
</rss>