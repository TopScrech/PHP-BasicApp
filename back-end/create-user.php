<?php

header('Content-Type: application/json');

if(!isset($_POST['name'])) {
    sendError('Name missing', __LINE__);
}

if (strlen($_POST['name']) < 2) {
    sendError('Name too short', __LINE__);
}

if (strlen($_POST['name']) > 20) {
    sendError('Name too long', __LINE__);
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
    INSERT INTO users
    VALUES (null, :name, :pictureName)
');

    $query->bindValue(':name', $_POST['name']);
    $query->bindValue(':pictureName', $uniqueName);
    $query->execute();

    $userId = $db->lastInsertId();

    // move tmp pic to /pictures
    $destinationFolder = __DIR__.'/pictures/';
    $finalPath = $destinationFolder.$uniqueName;

    move_uploaded_file($_FILES['picture']['tmp_name'], $finalPath);

    echo '{"status": 1, "message": "user created", "id": '.$userId.'}';

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