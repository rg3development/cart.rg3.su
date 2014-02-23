<div class="content2">
    <h2>Найдите ближайший магазин, где можно купить нашу продукцию</h2>

    <form action="" method="post" id="city_form">
        <div class="town_selector">
            <span>Ваш город:</span>
            <select name="city" id="city_select">
                <? foreach ( $town_select as $key => $value ) : ?>
                    <option name="city" value="<?= $key; ?>" <?= ( $value ) ? 'selected="selected"' : ''; ?>>
                        <?= $key; ?>
                    </option>
                <? endforeach; ?>
            </select>
        </div>
    </form>

    <div id="map_canvas"></div>

    <? if ( isset($shop_list) && !empty($shop_list) ) : ?>
        <? foreach ( $shop_list as $key => $shop ) : ?>
            <div class="cell2">
                <div class="col11">
                    <h3>
                        <?= $shop->title; ?>
                    </h3>
                </div>
                <div class="col22">
                    <p>
                        <?= $shop->address; ?>
                        <br>
                        <span><?= $shop->work_time; ?></span>
                    </p>
                </div>
                <div class="col33">
                    <p>
                        <?= $shop->phones; ?>
                    </p>
                </div>
            </div>
        <? endforeach; ?>
    <? endif; ?>

</div>

<script type="text/javascript">

    $(document).ready(function() {

        $('#city_select').change(function () {
            $('#city_form').submit();
        });

        var city_lat = <?= "'{$cur_city->latitude}'"; ?>;
        var city_lon = <?= "'{$cur_city->longitude}'"; ?>;
        /*
            [0] - map element   [1] - zoom  [2] - latitude  [3] - longitude
        */
        var mapSettings = [
            'map_canvas', 10, city_lat, city_lon
        ];

        /*
            [0] - shop    [1] - address    [2] - worktime     [3] - phone       [4] - latitude    [5] - longitude
        */
        var locations = [];

        <? if ( isset($shop_list) && !empty($shop_list) ) : ?>
            <? foreach ( $shop_list as $key => $shop ) : ?>
                var shop_info = [
                    <?= "'{$shop->title}'"; ?>,
                    <?= "'{$shop->address}'"; ?>,
                    <?= "'{$shop->work_time}'"; ?>,
                    <?= "'{$shop->phones}'"; ?>,
                    <?= "'{$shop->latitude}'"; ?>,
                    <?= "'{$shop->longitude}'"; ?>
                ];
                locations.push(shop_info);
            <? endforeach;?>
        <? endif; ?>

        map_init(mapSettings, locations);
    });

</script>