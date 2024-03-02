<?php
try {
    $conn = new PDO("mysql:host=localhost;port=3308;dbname=shop_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
