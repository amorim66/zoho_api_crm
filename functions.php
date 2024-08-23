<?php
require_once 'config.php';

function getZohoConfig() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $sql = "SELECT * FROM zoho_config WHERE id = 1 LIMIT 1";
    $result = $conn->query($sql);
    $conn->close();
    
    return $result->fetch_assoc();
}

function updateZohoConfig($accessToken, $refreshToken, $expiresAt) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $sql = "UPDATE zoho_config SET 
            access_token = '$accessToken', 
            refresh_token = '$refreshToken', 
            expires_at = $expiresAt 
            WHERE id = 1";
    $conn->query($sql);
    $conn->close();
}

function insertZohoConfig($accessToken, $refreshToken, $expiresAt) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $sql = "INSERT INTO zoho_config (access_token, refresh_token, expires_at) 
            VALUES ('$accessToken', '$refreshToken', $expiresAt)";
    $conn->query($sql);
    $conn->close();
}
?>
