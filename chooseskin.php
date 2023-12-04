<?php
    $configs = include('./config.php');
    $public_link = $configs['public_link'];
    //get infor form database

    $skinId = urldecode($_REQUEST["skinID"]);
    //get return object
    $json = array();
    $bus = array(
        'status' => true,
        'id' => $skinId,
        'content' => '<img src="'. $public_link . 'img/equipment/body/' .$skinId . '.png" class="inner-image">',
        'message' => 'Succeess'
    );
    //array_push($json, $bus);
    
    $jsonstring = json_encode($bus);
    echo $jsonstring;

?>