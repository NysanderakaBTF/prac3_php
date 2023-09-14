<?php

require_once '../ImagesAPI.php';

try {
    $api = new productAPI();
    echo $api->run();
} catch (Exception $e) {
    echo json_encode(array('error' => $e->getMessage()));
}