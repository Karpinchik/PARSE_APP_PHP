<?php
require_once "simple_html_dom.php";
require_once "functions.php";
require_once __DIR__ ."/config/db_data.php";

// переменная для хранения объекта страницы
$output = '';
$url_mine_page = 'https://realt.by/rent/flat-for-day/';
//$url_mine_page = trim(fgets(STDIN));   // для запуска через консоль
$url = $url_mine_page . '?search=all&page=';

$ref_paging = [];
$items_all = [];


// получаю объект пагинацию главной страницы
get_content_html($url_mine_page);


// получаю номер последней страницы из объекта пагинации
$html_paging = new simple_html_dom();  // объект пагинации (ссылки на все страницы)
$html_paging= str_get_html( $output );
$element = $html_paging->find( ".uni-paging a" );
foreach($element as $item)
{
    $ref_paging[] = $item->getAttribute('href');
}
$last_items = explode('=', end($ref_paging));
$count = $last_items[1];   // номер последней страницы
//$count = 5;   // для теста
$html_paging->clear();

// цмклом прохожу по каждой странице каталога, забираю данные и формирую массив-каталог
$i = 1;
while( $i<=$count ) {
    get_content_html($url . $i);
    $html_page= str_get_html( $output );
    $page_items =  $html_page->find('.bd-item');
    foreach ($page_items as $item)
    {
        $items_all[] =  array(
            $item->children(2)->children(0)->getElementByTagName('.fr')->plaintext,    // артикул
            $item->children(0)->children(0)->children(0)->children(0)->plaintext,       // title
            $item->children(1)->children(0)->children(2)->children(0)->getAttribute('data-original'),       // img
            $item->children(2)->children(0)->getElementByTagName('p')->plaintext,           //  date
            intval(str_replace('руб/сутки', '', $item->children(1)->children(1)->getElementByTagName('p .price-byr')->plaintext)),        //cost
            $item->children(2)->children(2)->plaintext,             // phone
            $item->children(2)->children(1)->children(1)->plaintext,             // description
            intval($item->children(0)->children(0)->children(0)->children(0)->getElementByTagName('strong')->plaintext)  // комнаты
        );
    }
    $i +=1;
}

set_catalog_db($items_all);

$html_page->clear();
unset($items_all);
header('Location: app_catalog.php');


