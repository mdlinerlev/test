<?php



const PRIZES = array (

    array (
        'name' => '1.000.000 рублей',
        'price' => 1000000
    ),
    array (
        'name' => '750.000 рублей',
        'price' => 750000
    ),
    array (
        'name' => '600.000 рублей',
        'price' => 600000
    ),
    array (
        'name' => '400.000 рублей',
        'price' => 400000
    ),
    array (
        'name' => '250.000 рублей',
        'price' => 250000
    ),
    array (
        'name' => '150.000 рублей',
        'price' => 150000
    ),
    array (
        'name' => '100.000 рублей',
        'price' => 100000
    ),

);





/* Тесты

$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;
$f = 0;
$g = 0;





for ( $i = 0; $i < 1000; $i++ )
{
    $x = startLottery( PRIZES );
    echo PRIZES[$x]['name'] . '<br>';

    switch ( PRIZES[$x]['price'] )
    {
        case 1000000: { $a++; break; }
        case 750000: { $b++; break; }
        case 600000: { $c++; break; }
        case 400000: { $d++; break; }
        case 250000: { $e++; break; }
        case 150000: { $f++; break; }
        case 100000: { $g++; break; }
    }
}


echo '<br>';

echo $a . ' (' . 1 / PRIZES[0]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';
echo $b . ' (' . 1 / PRIZES[1]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';
echo $c . ' (' . 1 / PRIZES[2]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';
echo $d . ' (' . 1 / PRIZES[3]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';
echo $e . ' (' . 1 / PRIZES[4]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';
echo $f . ' (' . 1 / PRIZES[5]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';
echo $g . ' (' . 1 / PRIZES[6]['price'] / totalChance( PRIZES ) * 100 . '%)' . '<br>';

*/





if ( $_POST['type'] == 'lot' )
{



    $i = startLottery( PRIZES ); // Запуск рулетки, получение индекса приза в массиве


    $result = array (
        'success' => 'success',
        'prize' => PRIZES[$i]['name'] // Название массива, которое будет выведено пользователю
    );

    echo json_encode( $result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ); // Конвертируем массив $result в JSON и распечатываем



}





function startLottery( array $prizes )
{

    $rand = mt_rand( 1, 100 ); // Рандомное число

    $current_chance = $prev_chance = 0; // Вероятность выпадения текущего и предыдущего призов. Пока что приравниваем к нулю.



    foreach ( PRIZES as $key => $params )
    {

        $current_coefficient = 1 / $prizes[$key]['price']; // Коэффициент текущего приза
        $current_chance += $current_coefficient / totalChance( $prizes ) * 100; // Вероятность выпадения текущего приза в процентах


        if ( $key != 0 ) // Проверяем, на какой итерации находится цикл по индексу массива (0 - первый индекс)
        {
            $prev_coefficient = 1 / $prizes[$key - 1]['price']; // Коэффициент предыдущего приза
            $prev_chance += $prev_coefficient / totalChance( $prizes ) * 100; // Вероятность выпадения предыдущего приза в процентах
        }


        if ( $rand > $prev_chance and $rand <= $current_chance ) // Если $rand находится в промежутке двух высчитанных шансов, то возращаем индекс текущего приза в массиве
            return $key;

    }



    return -1;

}



function totalChance( array $prizes )
{
    $result = 0;


    foreach ( $prizes as $key => $params )
    {
        $result += ( 1 / $params['price'] ); // Суммируем коэффициенты призов
    }


    return $result;
}
