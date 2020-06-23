<?php
require "simple_html_dom.php";
require "functions.php";


// парсинг по страницам из фильтра по комнатам
$output = '';           // переменная для хранения объекта страницы
$url_mine_page = 'https://realt.by/rent/flat-for-day/';
$url = 'https://realt.by/rent/flat-for-day/';
$rooms = ['1k/', '2k/', '3k/', '4k/'];
$items = [];   //  объявления

//get_flats_be_rooms($url, $rooms[1]);

foreach ($rooms as $room)
{
    get_flats_be_rooms($url, $room);
}


echo "<pre>";
print_r($items);



