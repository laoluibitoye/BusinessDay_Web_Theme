<?php
/**
 * My Account Page Access
 */

// Configuration
$loginUrl = 'https://stg18326.businessday.ng/Login/';
$selfcareOrigin = 'https://businessdaytest-selfcare.magnaquest.com';
$selfcareMyAccountUrl = $selfcareOrigin . '/#/account';
$checkoutOrigin = 'https://businessdaytest.magnaquest.com';

if (!is_user_logged_in()) {
    wp_redirect($loginUrl);
    exit;
}
?>

<div style="width:100%; height:100vh; overflow:hidden;">

    <iframe
        id="mqIframe"
        src="<?php echo esc_url($selfcareMyAccountUrl); ?>"
        width="100%"
        height="100%"
        style="border:none;">
    </iframe>

</div>

<script>


const SELFCARE_ORIGIN = "<?php echo esc_js($selfcareOrigin); ?>";
const CHECKOUT_ORIGIN = "<?php echo esc_js($checkoutOrigin); ?>"

window.addEventListener("load", function () {

    const iframe = document.getElementById("mqIframe");
    const token = localStorage.getItem("selfcareJWT");

    console.log("JWT:", token);

    setTimeout(function () {

        iframe.contentWindow.postMessage(
            {
                type: "SET_JWT",
                token: token
            },
            SELFCARE_ORIGIN
        );
        console.log("JWT sent to iframe");
    }, 3000);
});


/* Listen for messages from iframe */
window.addEventListener("message", function (event) {

    console.log("Message received on My Account Success:", event);

    if(event.origin === SELFCARE_ORIGIN){
        
	console.log("Triggering selfcare origin");
	if (event.data.type === "ACCOUNT_SUCCESS") {

        console.log("My Account Redirection Success");

        const iframe = document.getElementById("mqIframe");

        if (iframe) {
            iframe.src = iframe.src;
        }
    }
  }


  // Listen for messages from iframe on subscription complete
  if (event.origin === CHECKOUT_ORIGIN) {

        console.log("Triggering checkout origin");

        if (event.data.type === "SUBSCRIPTION_COMPLETED") {

            console.log("Subscription Renewed");
	    const iframe = document.getElementById("mqIframe");
              if (iframe) {
            	iframe.src = iframe.src;
              }
        }
    }

});

</script>