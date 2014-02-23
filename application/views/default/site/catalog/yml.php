<?
header('Content-Type: text/xml; charset=utf-8');
print('<?xml version="1.0" encoding="utf-8"?>' . "\n");
print('<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n");
?>

<yml_catalog date="<?= $yml['catalog_date']; ?>">
    <shop>
        <name><?= $yml['shop_name']; ?></name>
        <company><?= $yml['shop_company']; ?></company>
        <url><?= $yml['shop_url']; ?></url>

        <currencies>
            <currency id="<?= $yml['currency_id']; ?>" rate="1" plus="0"/>
        </currencies>

        <? if ( isset($yml['categories']) && !empty($yml['categories']) ) : ?>
            <categories>
                <? foreach ( $yml['categories'] as $key => $category ) : ?>
                    <category id="<?= $category->id; ?>" <?= ( $category->parent_category_id ) ? 'parentId="' . $category->parent_category_id . '"' : '' ; ?>>
                        <?= $category->title; ?>
                    </category>
                <? endforeach; ?>
            </categories>
        <? endif; ?>

        <local_delivery_cost><?= $yml['local_delivery_cost']; ?></local_delivery_cost>

        <? if ( isset($yml['offers']) && !empty($yml['offers']) ) : ?>
            <offers>
                <? foreach ( $yml['offers'] as $key => $offer ) : ?>
                    <offer id="<?= $offer->id; ?>" available="<?= $yml['offer_available']; ?>">
                        <url><?= $offer->url(); ?></url>
                        <price><?= $offer->price; ?></price>
                        <currencyId>
                            <?= $yml['currency_id']; ?>
                        </currencyId>
                        <categoryId>
                            <?= $offer->category()->id; ?>
                        </categoryId>
                        <? $images = $offer->images(); ?>
                        <? if ( !empty($images) ) : ?>
                            <? foreach ( $images as $product_image ) : ?>
                                <picture><?= $offer->img_src($product_image, TRUE); ?></picture>
                            <? endforeach; ?>
                        <? endif; ?>
                        <? if ( $yml['model'] ) : ?>
                            <vendor><?= $yml['vendor']; ?></vendor>
                            <model><?= $offer->title; ?></model>
                        <? else: ?>
                            <name><?= $offer->title; ?></name>
                        <? endif; ?>
                        <description><?= $offer->clean_string(strip_tags($offer->description)); ?></description>
                    </offer>
                <? endforeach; ?>
            </offers>
        <? endif; ?>

    </shop>
</yml_catalog>