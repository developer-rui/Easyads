<?php 

include_once("common/common.php");
include_once("common/db.php");
include_once("login_register/login.php");

include_once("components/navbar.php"); 
include_once("components/footer.php");
include_once("components/search_item_card.php");
include_once("components/alert.php");

secure_inputs();
init_session();
page_history("confirm_payment_page.php");

remember_user_if_needed();


if(!isset($_SESSION["payment_ad_id"]) || !isset($_SESSION["payment_validation_code"])){
    to_page("index.php");
}

$info = db()->query("SELECT title, text FROM ads WHERE id = ?", array($_SESSION["payment_ad_id"]));
if($info){
    $info = $info[0];
}else{
    to_page("index.php");
}


?>

<!DOCTYPE html>
<html>
  <head>
    <title>Confirm payment</title>
    
    <!-- Bootstrap CSS -->
    <link href="public_css/bootstrap.css" rel="stylesheet">
    
    <!-- custom CSS -->
    <link href="common/style/style_1.css" rel="stylesheet">
    <link href="components/style/style_navbar.css" rel="stylesheet">
    <link href="components/style/style_search_item_card.css" rel="stylesheet">
    
    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>    
    
  </head>
  <body>
       
       <div class="container-fluid" id="main_container">
           <?php display_navbar(); ?>
              
            <div class="row pt-5" id="main_section">
               <div class="col-1 col-lg-2"></div>
               <div class="col-10 col-lg-8">
                    <p class="mx-3">Please confirm that you want to make the following ad premium for $4.99:</p>
                    
                    <?php 
                        display_search_item_card($info["title"], 
                                                 $info["text"], 
                                                 $_SESSION["payment_ad_id"]);                                      
                    ?>
                    
                    <?php
                    display_alert_warning("This is a test payment. Please use 4242 4242 4242 4242 as your card number. DO NOT USE YOUR REAL INFORMATION!");
                    ?>
                    
                    <button type="button" id="checkout-button" class="btn-warning btn ms-3 mb-3">Checkout</button>
                    <a type="button" href="item_page.php?ad=<?php echo $_SESSION["payment_ad_id"]; ?>" class="btn-primary btn ms-3 mb-3">Cancel</a>                    

               </div>
               <div class="col-1 col-lg-2"></div>
           </div>
           
            <?php display_footer(); ?>
       </div>     
      
  </body>
  
  <!-- jquery -->
    <script src="public_js/jquery.js"></script>

    <!-- Bootstrap JS -->
    <script src="public_js/bootstrap.js"></script>
    
    <script type="text/javascript">
        $(document).ready(() => {
            $("#main_section").css({                
                "margin-top": $("nav").outerHeight() + "px"                
            });

        });
    </script>
  
  <script type="text/javascript">
    
    // Create an instance of the Stripe object ****with your publishable API key****
    var stripe = Stripe("pk_test_51J2I46K1hgwt6k6MuDbQCC8F3skwnBz2XSWVf6gmMoLSnLNMqp6YaZxLqoT1fWqy1HaUAOnHrBb70yh6n5UX0VBs00W3l50Pim");
    

    document.getElementById("checkout-button").addEventListener("click", function () {
      
      // fetch ****l'adresse complet http de la create-checkout-session.php****
      fetch("https://easyads-app.herokuapp.com/payment/payment_action.php", { // ### Appeler notre create_session.php
        method: "POST",
      })
        .then(function (response) {          
          return response.json();
        })
        .then(function (session) {// Si tout va bien, rediriger à Stripe avec l'id de la session 
          return stripe.redirectToCheckout({ sessionId: session.id }); 
        })
        .then(function (result) {
          // If redirectToCheckout fails due to a browser or network error,
          // you should display the localized error message to your customer using error.message          
          if (result.error) {  alert(result.error.message); }
        })
        .catch(function (error) {
            alert("***Erreur*** " + error); //console.error("Error:", error);          
        });
    });
  </script>
</html>




