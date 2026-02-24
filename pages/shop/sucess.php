<?php
require 'config.php';
require 'paypal_token.php';

$orderId = $_GET['token'];

$ch = curl_init("$paypalUrl/v2/checkout/orders/$orderId/capture");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data['status'] == 'COMPLETED') {
    echo "Paiement réussi !";
} else {
    echo "Erreur lors du paiement.";
}