<?php


include_once("../common/common.php");
include_once("create_checkout_session.php");

init_session();
secure_inputs();


echo create_checkout_session(
    'sk_test_51J2I46K1hgwt6k6MhUu2Ad9vY0ASfTByjwzyM5yub8bHUmeLeBSTOMc1lUTHKdBfBdF4eFiWtsoHc381OeX30gS2004S7PclvN', 
    "premium ad",
    "4.99",
    "payment/payment_completed_action.php",
    $_SESSION["payment_validation_code"]
);

