<?

function get_month_name($numbers = 1, $lang = 'ru') {
    $month       = array();
    $month['ru'] = array('','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
    return (string)$month[$lang][$numbers];
}