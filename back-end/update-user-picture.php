<?php

header('Content-Type: application/json');

if(!isset($_GET['id'])) {
    sendError('id missing', __LINE__);
}

if(!ctype_digit($_GET['id'])) {
    sendError('id must be an int', __LINE__);
}

// Pic validation
if(!isset($_FILES['picture'])) {
    sendError('Picture missing', __LINE__);
}

var_dump($_FILES['picture']);
//name 37971E01-C900-40D6-9559-17852B100245_under1mb_q100 copy.jpg
// type image/jpeg
// tmp_name /Applications/MAMP/tmp/php/php5oNiEE
// size 1040677 B

$extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION); // jpg

$allowedExtensions = array("jpg", "jpeg", "png", "gif", "heic", "heif");

if(!in_array($extension, $allowedExtensions)) {
    sendError('Invalid file extension, allowed extensions are: '.implode(', ', $allowedExtensions).'', __LINE__);
}

if($_FILES['picture']['size'] > 5000000) {
    sendError('Picture too big', __LINE__);
}

// Unique name
$uniqueName = bin2hex(random_bytes(16)); // 32 chars
$uniqueName .= '.'.$extension; // c688ab69771aa1becbd5081104cb2bbe.jpg

echo $uniqueName;

require_once(__DIR__.'/protected/database.php');

try {
    $query = $db->prepare('
    UPDATE users SET picture_name = :picture_name WHERE id = :id
');
    $query->bindValue(':picture_name', $uniqueName);
    $query->bindValue(':id', $_GET['id']);
    $query->execute();

    if(!$query->rowCount($_FILES['picture']['tmp_name'], )) {
        sendError('No user was affected', __LINE__);
    }

    $destinationFolder = __DIR__.'/pictures/';
    $finalPath = $destinationFolder.$uniqueName;

    move_uploaded_file($_FILES['picture']['tmp_name'], $finalPath);

    echo '{"status": 1, "message": "user pic updated"}';
    exit();
} catch(PDOException $ex) {
    sendError("cannot create user", __LINE__);
}

exit();

function sendError($message = 'error', $debug = 0) {
    echo '{
        "status": 0, 
        "message": "'.$message.'",
        "debug": '.$debug.'
    }';

    exit();
}