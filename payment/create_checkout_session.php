<?php

// **** Chemin du fichier init.php****
include_once('stripe_lib/init.php');

function create_checkout_session($secretAPIKey, $product_name, $price, $redirect_page, $validationCode){
    
    //Clé secrète
    \Stripe\Stripe::setApiKey($secretAPIKey);  
    
    header('Content-Type: application/json');
    // **** le chemin complet http du dossier contenant les pages du projet****
    $YOUR_DOMAIN = 'https://easyads-app.herokuapp.com/';//'http://easyads/';

    // La session de paiement
    $checkout_session = \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card'],
      'line_items' => [
        [
            'price_data' => [
                'currency' => 'CAD',
                'unit_amount' => ($price * 100), //attention c'est en cents! c'est pour ça on multiplie par 100
                'product_data' => [
                    'name' => $product_name,                    
                ]
            ],
            'quantity' => 1,
        ]
      ],
      'mode' => 'payment',
      'success_url' => $YOUR_DOMAIN . $redirect_page . '?c=' . $validationCode,
      'cancel_url' => $YOUR_DOMAIN . $redirect_page
    ]);

    return json_encode(['id' => $checkout_session->id]);
}





