<?

$page_data['widgets'] = array();

/* get widget data */

$footer_menu = $this->page_mapper->get_menu(0, 0);
$main_slider = $this->banner_mapper->get_widget(1);
$text_banner = $this->text_mapper->get_widjet(1);

/* set template data */

$page_data['widgets']['footer_menu'] = $footer_menu[0];
$page_data['widgets']['text_banner'] = $text_banner;
$page_data['widgets']['main_slider'] = $main_slider;

?>