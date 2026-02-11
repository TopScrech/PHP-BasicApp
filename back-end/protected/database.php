<?php

try {
    $dbUserName = 'root';
    $dbPassword = 'your_password';
    $dbConnection = 'mysql:host=localhost; dbname=ImageUpload; charset=utf8';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // try catch
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // JSON
    ];
    $db = new PDO($dbConnection, $dbUserName, $dbPassword, $options);
} catch(DPOException $ex) {
    echo '{
        "status": 0,
        "message": "Cannot connect to database",
        "debug": '.__LINE__.'
    }';
    exit;
}