<?php
require __DIR__ . '/../redis/session.php'; // correct path

try {
    $ping = $redis->ping();
    echo "Redis is connected! Response: " . $ping;
} catch (Exception $e) {
    echo "Redis connection failed: " . $e->getMessage();
}
?>
