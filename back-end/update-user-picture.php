<?php
header('Access-Control-Allow-Origin: *');
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

if($_FILES['picture']['error'] !== UPLOAD_ERR_OK) {
    sendError('Picture upload failed', __LINE__);
}

$extension = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION)); // jpg

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

$destinationFolder = __DIR__.'/pictures/';
$finalPath = $destinationFolder.$uniqueName;

if(!is_dir($destinationFolder)) {
    mkdir($destinationFolder, 0777, true);
}

if(!move_uploaded_file($_FILES['picture']['tmp_name'], $finalPath)) {
    sendError('Cannot move uploaded file', __LINE__);
}

require_once(__DIR__.'/protected/database.php');

try {
    $query = $db->prepare('
    UPDATE users SET picture_name = :picture_name WHERE id = :id
');
    $query->bindValue(':picture_name', $uniqueName);
    $query->bindValue(':id', $_GET['id']);
    $query->execute();

    if(!$query->rowCount()) {
        if(file_exists($finalPath)) {
            unlink($finalPath);
        }
        sendError('No user was affected', __LINE__);
    }

    echo '{"status": 1, "message": "user pic updated", "picture_name": "'.$uniqueName.'"}';
    exit();
} catch(PDOException $ex) {
    if(file_exists($finalPath)) {
        unlink($finalPath);
    }
    sendError("cannot update user picture", __LINE__);
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
