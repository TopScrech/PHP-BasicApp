<?php

header('Content-Type: application/json');

if(!isset($_GET['id'])) {
    sendError('id missing', __LINE__);
}

if (!ctype_digit($_GET['id'])) {
    sendError('id must be an int', __LINE__);
}

require_once(__DIR__.'/protected/database.php');

try {
    $query = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $query->bindValue(':id', $_GET['id']); // Anti SQL injection
    $query->execute();
    $row = $query->fetch();

    if(!$row) {
        sendError('id does not exist', __LINE__);
    }

    echo '{"status": 1, "data": '.json_encode($row).' }';
    exit();
} catch(PDOException $ex) {
    sendError('error executing query', __LINE__);
}

echo '{"status": 0, "data": {"id": 1, "name": "Goida"}}';