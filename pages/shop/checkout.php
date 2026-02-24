<?php
require 'config.php';

// exemple produit
$product = [
    'name' => 'T-shirt cool',
    'price' => 19.99
];

require 'paypal_token.php'; // récupère $accessToken

$body = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "EUR",
            "value" => $product['price']
        ],
        "description" => $product['name']
    ]],
    "application_context" => [
        "return_url" => "http://localhost/success.php",
        "cancel_url" => "http://localhost/cancel.php"
    ]
];

$ch = curl_init("$paypalUrl/v2/checkout/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$approveLink = $data['links'][1]['href']; // lien pour rediriger l'utilisateur
header("Location: $approveLink");
exit;