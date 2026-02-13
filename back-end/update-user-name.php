<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$name = $_POST['name'] ?? $_GET['name'] ?? null;

if(!$name) {
    sendError('Name missing', __LINE__);
}

if (strlen($name) < 2) {
    sendError('Name too short', __LINE__);
}

if (strlen($name) > 20) {
    sendError('Name too long', __LINE__);
}

if(!isset($_GET['id'])) {
    sendError('id missing', __LINE__);
}

if(!ctype_digit($_GET['id'])) {
    sendError('id must be an int', __LINE__);
}

require_once(__DIR__.'/protected/database.php');

try {
    $query = $db->prepare('
    UPDATE users SET name = :name WHERE id = :id
');
    $query->bindValue(':name', $name);
    $query->bindValue(':id', $_GET['id']);
    $query->execute();

    if(!$query->rowCount()) {
        sendError('No user was affected', __LINE__);
    }

    echo '{"status": 1, "message": "user name updated"}';
    exit();
} catch(PDOException $ex) {
    sendError("cannot create user", __LINE__);
}

function sendError($message = 'error', $debug = 0) {
    echo '{
        "status": 0, 
        "message": "'.$message.'",
        "debug": '.$debug.'
    }';

    exit();
}
