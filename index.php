<?php

$db = new mysqli('localhost', 'root', '', 'brainforce');

if($db->connect_errno){
    echo 'No connect: ' . $db->connect_error;
    die;
}

echo '<table border="1">';
$remh;
$reml;
$ft = 1;

if ($res2 = $db->query("SELECT `id`, `price_rub`, `price_opt_rub` FROM `pricelist`")){
    while ($row2 = $res2->fetch_assoc()){
        
        if ($ft === 1){
           $hp = $row2['price_rub'];
           $lop = $row2['price_opt_rub'];
           $ft = 0;
           $remh = $row2['id'];
           $reml = $row2['id'];
        }else{
            if ($row2['price_rub'] > $hp){
                $hp = $row2['price_rub'];
                $remh = $row2['id'];
            }
            if ($row2['price_opt_rub'] < $lop){
                $lop = $row2['price_opt_rub'];
                $reml = $row2['id'];
            } 
        }
    }
}

if($res = $db->query("SELECT * FROM `pricelist`")){
    echo '<tr>
    <td>Имя</td>
    <td>Цена</td>
    <td>Оптовая цена</td>
    <td>Наличие на складе 1</td>
    <td>Наличие на складе 2</td>
    <td>Страна производитель</td>
    <td>Примечание</td>
    </tr>';
    $sp = 0;
    $sop = 0;
    $st1 = 0;
    $st2 = 0;
    $amount = 0;
   
    while ($row = $res->fetch_assoc()){
        $amount++;
        echo '<tr>';
        echo '<td>';
        echo $row['name'];
        echo '</td>';
        
        if($row['price_rub'] === $hp){
            echo '<td bgcolor="#F56E77">';
            echo $row['price_rub'];
            $sp += $row['price_rub'];
            echo '</td>';
        }else{
            echo '<td>';
            echo $row['price_rub'];
            $sp += $row['price_rub'];
            echo '</td>';
        }
        
        if($row['price_opt_rub'] === $lop){
            echo '<td bgcolor="#7DF585">';
            echo $row['price_opt_rub'];
            $sop += $row['price_opt_rub'];
            echo '</td>';
        }else{
            echo '<td>';
            echo $row['price_opt_rub'];
            $sop += $row['price_opt_rub'];
            echo '</td>';
        }
        
        echo '<td>';
        echo $row['storage1_pcs'];
        $st1 += $row['storage1_pcs'];
        echo '</td>';
        
        echo '<td>';
        echo $row['storage2_pcs'];
        $st2 += $row['storage2_pcs'];
        echo '</td>';
        
        echo '<td>';
        echo $row['country'];
        echo '</td>';
        
        $sks = $row['storage1_pcs'] + $row['storage2_pcs'];
        if($sks < 20){
            echo '<td bgcolor="#F5D3CA">';
            echo 'Осталось мало!! Срочно докупите!!!';
            echo '</td>';            
        }else{
            echo '<td>';
            echo '-';
            echo '</td>';
        }
        
        echo '</tr>';
    }
    echo '<tr>';
    echo '<td>Имя</td>';
    echo '<td> Средняя цена: ' 
    . round($sp/$amount, PHP_ROUND_HALF_UP) 
            . '</td>';
    echo '<td>Средняя опт. цена: ' 
    . round($sop/$amount, PHP_ROUND_HALF_UP) 
            . '</td>';
    echo '<td> Всего на складе 1: ' . $st1 . '</td>';
    echo '<td> Всего на складе 2: ' . $st2 . '</td>';
    echo '<td>Страна производитель</td>';
    echo '<td>Примечание</td>';
    echo '</tr>';
}

echo '</table>';




