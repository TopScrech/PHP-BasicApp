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
    $query = $db->prepare('DELETE FROM users WHERE id = :id');
    $query->bindValue(':id', $_GET['id']); // Anti SQL injection
    $query->execute();

    if(!$query->rowCount()) {
        sendError('user not found', __LINE__);
    }

    echo '{"status": 1, "message": "user deleted" }';
    exit();
} catch(PDOException $ex) {
    sendError('error executing query', __LINE__);
}

echo '{"status": 0, "data": {"id": 1, "name": "Goida"}}';