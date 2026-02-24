<?php
require 'config.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$paypalUrl/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$accessToken = $data['access_token'];