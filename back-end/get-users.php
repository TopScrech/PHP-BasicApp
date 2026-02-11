<?php

header('Content-Type: application/json');

// The next line replaces itself with database.php
require_once(__DIR__.'/protected/database.php');

try {
    $query = $db->prepare('SELECT * FROM users');
    $query->execute();
    $rows = $query->fetchAll();
    echo '{"status": 1, "data": '.json_encode($rows).' }';
    exit();
} catch(PDOException $ex) {
    sendError('error executing query', __LINE__);
}

function sendError($message = 'error', $debug = 0) {
    echo '{
        "status": 0, 
        "message": "'.$message.'"
        "debug": '.$debug.'
    }';

    exit();
}