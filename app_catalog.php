<?php
require "./config/db_data.php";
require "functions.php";

$count_rooms = $_POST['count_rooms'];
$cost = $_POST['cost'];
$arr_items = [];


function get_all_items()
{
    global $dsn, $user, $password, $opt;
    global $errors, $arr_items;

    try {
        $pdo = new PDO($dsn, $user, $password, $opt);
        $stmt = $pdo->query("SELECT * FROM `realt`");

        while($result = $stmt->fetch()){
            $arr_items[] = $result;
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        print_r($errors);
    }

}

function get_items($count_rooms, $cost)
{
    global $dsn, $user, $password, $opt;
    global $errors, $arr_items;

    try {
        $pdo = new PDO($dsn, $user, $password, $opt);
        $stmt = $pdo->query("SELECT * FROM `realt` WHERE `rooms` = '$count_rooms' AND cost < '$cost'");

        while($result = $stmt->fetch()){
            $arr_items[] = $result;
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        print_r($errors);
    }

}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    get_items($count_rooms, $cost);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    get_all_items();
}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<footer><h1>realt</h1></footer>

<div class="filter">
    <form method="post" action="">
        <label>количство комнат 1 - 4</label>
        <input type="number" name="count_rooms" placeholder="" value="<?php echo @$_POST['count_rooms']; ?>">
        <label>сумма</label>
        <input type="number" name="cost" placeholder="" value="<?php echo @$_POST['cost']; ?>">
        <input type="submit" name="go">
        <hr>
    </form>
</div>

<div>
    <?php foreach ($arr_items as $item){ ?>

        <div style="border: 2px solid grey;">
            <p><?php echo $item['title']; ?></p>
            <img src="<?php echo $item['img']; ?>">
            <p><?php echo $item['date_start']; ?>  - дата подачи объявления</p>
            <p><?php echo $item['cost']; ?> - стоимость</p>
            <p><?php echo $item['phone']; ?></p>
            <p><?php echo $item['description']; ?></p>
        </div>

    <?php } ?>
</div>

</body>
</html>

