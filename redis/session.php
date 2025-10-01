<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer autoload

$redis = new Predis\Client(); // default localhost:6379

function setSession($token, $email) {
    global $redis;
    $redis->setex("session:$token", 3600, $email); // token expires in 1 hour
}

function getSession($token) {
    global $redis;
    
    return $redis->get("session:$token");
}
?>
