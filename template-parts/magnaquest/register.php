<?php

// Configuration
$loginUrl = 'https://stg18326.businessday.ng/Login/';
$homeUrl = 'https://stg18326.businessday.ng/';

$selfcareOrigin = 'https://businessdaytest-selfcare.magnaquest.com';
$checkoutOrigin = 'https://businessdaytest.magnaquest.com';

$subscriptionUrl = $selfcareOrigin . '/#/account/mySubscription';

if (!is_user_logged_in()) {
    wp_redirect($loginUrl);
    exit;
}
?>

<div style="width:100%; height:100vh; overflow:hidden;">

    <iframe
        id="mqIframe"
        src="<?php echo esc_url($subscriptionUrl); ?>"
        width="100%"
        height="100%"
        style="border:none;">
    </iframe>

</div>

<script>

const subscribeURLS = {
    home: "<?php echo esc_js($homeUrl); ?>",
    subscription: "<?php echo esc_js($subscriptionUrl); ?>",
    selfcareOrigin: "<?php echo esc_js($selfcareOrigin); ?>",
    checkoutOrigin: "<?php echo esc_js($checkoutOrigin); ?>"
};

window.addEventListener("load", function () {

    const iframe = document.getElementById("mqIframe");
    const token = localStorage.getItem("selfcareJWT");

    console.log("Iframe:", iframe);
    console.log("JWT:", token);

    setTimeout(function () {

        console.log("Sending Message");

        iframe.contentWindow.postMessage(
            {
                type: "SET_JWT",
                token: token
            },
            subscribeURLS.selfcareOrigin
        );
        console.log("Message Sent");
     }, 3000);
});


/* Listen for messages from iframe */
window.addEventListener("message", function (event) {

    console.log("Message received:", event);

    // Selfcare iframe events
    if (event.origin === subscribeURLS.selfcareOrigin) {

        if (event.data.type === "SUBSCRIPTION_SUCCESS") {

            console.log("Subscription successful. Reloading iframe...");

            const iframe = document.getElementById("mqIframe");

            if (iframe) {
                iframe.src = iframe.src;
            }
        }

        return;
    }

    // Listen for messages from iframe on subscription complete
    if (event.origin === subscribeURLS.checkoutOrigin) {

        if (event.data.type === "SUBSCRIPTION_COMPLETED") {

            console.log("Subscription Completed. Redirecting...");

            window.location.href = subscribeURLS.home;
        }
    }

});

</script>