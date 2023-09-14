<?php
require_once '../UsersAPI.php';

try {
    $api = new UsersAPI();
    echo $api->run();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}