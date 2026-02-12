<?php

header('Content-Type: application/json');

require_once(__DIR__.'/protected/database.php');

try {
    $query = $db->prepare('DELETE FROM users');
    $query->execute();

    if(!$query->rowCount()) {
        sendError('users not found', __LINE__);
    }

    echo '{"status": 1, "message": "all users deleted" }';
    exit();
} catch(PDOException $ex) {
    sendError('error executing query', __LINE__);
}