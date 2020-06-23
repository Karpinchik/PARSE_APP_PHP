<?php
require_once __DIR__ ."/config/db_data.php";

/*
 * получение объекта запрашиваемой страницы
 * */
function get_content_html($url){
    global $output;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}


/*
 * функция парсить все обьявления по колличеству комнат
 * */
function get_flats_be_rooms($url, $rooms)
{
    global $output, $items;
    $html_page = new simple_html_dom();
    get_content_html($url . $rooms);

    $html_page = str_get_html( $output );
    $page_items = $html_page->find('.bd-item');
    foreach ($page_items as $item) {
        $items[] = array( str_replace('k/', '', $rooms),
                          $item->children(2)->children(0)->getElementByTagName('.fr')->plaintext,
                          $item->children(0)->children(0)->children(0)->children(0)->plaintext,
                          $item->children(1)->children(0)->children(2)->children(0)->getAttribute('data-original'),
                          $item->children(2)->children(0)->getElementByTagName('p')->plaintext,
                          $item->children(1)->children(1)->getElementByTagName('p .price-byr')->plaintext,
                          $item->children(2)->children(2)->plaintext,
                          $item->children(2)->children(1)->children(1)->plaintext
        );
    }
}


/*
 * запись каталога в БД
 * */
function set_catalog_db($items){
    global $dsn, $user, $password, $opt;

        $pdo = new PDO($dsn, $user, $password, $opt);

        $sql = 'TRUNCATE TABLE realt';
        $stmt1 = $pdo->query($sql);

        try {
        foreach ($items as $item) {
            $sql = "INSERT INTO `realt`(article, title, img, date_start, cost, phone, description, rooms) 
                    VALUES (:article, :title, :img, :date_start, :cost, :phone, :description, :rooms)";
            $stmt = $pdo->prepare($sql);
            $params = [':article' => $item[0], ':title' => $item[1], ':img' => $item[2], ':date_start' => $item[3],
                        ':cost' => $item[4],':phone' => $item[5],':description' => $item[6], ':rooms' => $item[7]];
            $stmt->execute($params);
        }
    } catch (Exception $e) {
        $err_msg =  $e->getMessage();
        echo $err_msg;
    }
}
