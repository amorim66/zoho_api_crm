<?php
require_once 'functions.php';

function getAccessToken($authCode = "1000.99b1fd551e9f3e3dd692c2a40846b551.295575f974835d57b2930daee7e006f1") {
    $config = getZohoConfig();
    
    if ($config && time() < $config['expires_at']) {
        return $config['access_token'];
    } elseif ($config) {
        return refreshToken($config['refresh_token']);
    } else {
        return generateTokens($authCode);
    }
}

function generateTokens($authCode) {
    $postFields = [
        'grant_type' => 'authorization_code',
        'client_id' => ZOHO_CLIENT_ID,
        'client_secret' => ZOHO_CLIENT_SECRET,
        'redirect_uri' => ZOHO_REDIRECT_URI,
        'code' => $authCode
    ];

    $ch = curl_init('https://accounts.zoho.com/oauth/v2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    
    if (isset($data['access_token'])) {
        insertZohoConfig($data['access_token'], $data['refresh_token'], time() + 3600);
        return $data['access_token'];
    }

    return false;
}

function refreshToken($refreshToken) {
    $postFields = [
        'grant_type' => 'refresh_token',
        'client_id' => ZOHO_CLIENT_ID,
        'client_secret' => ZOHO_CLIENT_SECRET,
        'refresh_token' => $refreshToken
    ];

    $ch = curl_init('https://accounts.zoho.com/oauth/v2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    
    if (isset($data['access_token'])) {
        updateZohoConfig($data['access_token'], $refreshToken, time() + 3600);
        return $data['access_token'];
    }

    return false;
}
?>
