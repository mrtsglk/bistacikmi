<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include 'simple_html_dom.php';

function get_bist(){
    $html = file_get_html('https://finans.mynet.com');

    $array = [];
    $DOM = new DOMDocument();
    foreach($html->find(".session-statistics") as $element)  {
        $DOM->loadHTML($element);
        $Detail = $DOM->getElementsByTagName('td');
        $elementArr = [];
        foreach($Detail as $NodeHeader)
        {
            $elementArr[] = trim($NodeHeader->textContent);
        }
        array_push($array, $elementArr);
    }

    $mergedArray = [];
    $artanArr = array_chunk($array[0], 4);
    $azalanArr = array_chunk($array[1], 4);
    $islemArr = array_chunk($array[2], 3);

    array_push($mergedArray, $artanArr);
    array_push($mergedArray, $azalanArr);
    array_push($mergedArray, $islemArr);

    $jsonData = json_encode($mergedArray, JSON_PRETTY_PRINT);
    file_put_contents("bist.json", $jsonData);
}


if(!file_exists("bist.json")){
    get_bist();
}else{
    $file_time = filectime("bist.json");
    if(date("Y-m-d") > date("Y-m-d", $file_time)){
        get_bist();
    }

}

$get_json = file_get_contents("bist.json");
$get_bist_array = json_decode($get_json, true);
?>
<div class="tables">
<?php
foreach($get_bist_array as $gbaKey => $gba){
    echo '<table>';
    if($gbaKey == 0){
        echo '<tr><td>ARTAN</td><td>Fiyat</td><td>Fark%</td><td>Saat</td></tr>';
    }

    if($gbaKey == 1){
        echo '<tr><td>AZALAN</td><td>Fiyat</td><td>Fark%</td><td>Saat</td></tr>';
    }

    if($gbaKey == 2){
        echo '<tr><td>İŞLEM</td><td>Hacim</td><td>Saat</td></tr>';
    }

    foreach($gba as $gb){
        echo '<tr>';
        foreach($gb as $g){
            echo '<td>'.$g.'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}
?>
</div>
<style>
    .tables{
        display:flex;
        gap:25px;
    }

    .tables table{
        width:calc(calc(100%/3) - 25px);
        background:#fff;
        color:#000;
        padding:15px;
    }

    .tables table tr td{
        padding:3px;
    }

    .tables table tr:first-child td{
        font-weight: bold;
    }
</style>

