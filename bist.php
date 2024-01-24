<link rel="stylesheet" href="bist-css.css">
<?php

$filename = 'bist.html';
if (file_exists($filename)) {
    $file_creation_date = filectime($filename);
    $datea = date("Y-m-d", $file_creation_date);
    if(date("Y-m-d") > $datea){
        $req = file_get_contents('https://finans.mynet.com/');
        file_put_contents('bist.html', $req);
    }
}else{
    $req = file_get_contents('https://finans.mynet.com/');
    file_put_contents('bist.html', $req);
}

$request = file_get_contents('bist.html');
$data=explode('<div class="row session-statistics-row">', $request);
$data=explode('</div>', $data[1]);

$table_1=explode('<div class="table-heading-type-1">', $data[0]);
$table_1=explode('</div>', $table_1[1]);

$table_11=explode('<span>', $table_1[0]);
$table_11=explode('</span>', $table_11[1]);

$table_2=explode('<div class="table-heading-type-1">', $data[3]);
$table_2=explode('</div>', $table_2[1]);

$table_22=explode('<span>', $table_2[0]);
$table_22=explode('</span>', $table_22[1]);
?>
<div class="row">
    <div class="col-12">
        <div class="row session-statistics-row">
            <div class="col-12 col-lg-4">
                <div class="card data-type-1 <?php if($table_11[0] == "Azalan"){ echo "data-status-down";} ?> data-type-hisse">
                    <div class="table-heading-type-1">
                        <?php print_r($table_1[0]); ?>
                    </div>
                    <?php print_r($data[1]); ?>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card data-type-1 <?php if($table_22[0] == "Azalan"){ echo "data-status-down";} ?> data-type-hisse">
                    <div class="table-heading-type-1">
                        <?php print_r($table_2[0]); ?>
                    </div>
                    <?php print_r($data[4]); ?>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card data-type-1 data-type-hisse">
                    <div class="table-heading-type-1">
                        <span>İşlem</span>
                    </div>
                    <?php print_r($data[7]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
