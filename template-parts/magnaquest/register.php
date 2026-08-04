<?php

// Configuration
// Environment-driven (Settings -> Theme Environment) — was hardcoded to staging here.
$loginUrl = bd_get_env_url('login_page_url');
$homeUrl = bd_get_env_url('home_url');

$selfcareOrigin = bd_get_env_url('selfcare_origin');
$checkoutOrigin = bd_get_env_url('checkout_origin');

$subscriptionUrl = $selfcareOrigin . '/#/account/mySubscription';

if (!is_user_logged_in()) {
    wp_redirect($loginUrl);
    exit;
}

// Group members' subscription is the group owner's Magnaquest contract, not their own --
// per the group-accounts spec they should never reach the Subscribe page. See
// handle_group_signup() in functions/magnaquest-api.php for where _bd_is_group_member gets set.
if (get_user_meta(get_current_user_id(), '_bd_is_group_member', true)) {
    wp_redirect(home_url('/'));
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

// FIX: this used to arm the iframe's own "load" listener from inside window's
// "load" event -- but the browser fires the iframe's "load" before (or as part
// of) the parent window's "load", so that listener was almost always attached
// too late to ever fire, silently cutting the intended 6-attempt staggered
// retry down to ~2 blind attempts. This <script> tag runs synchronously right
// after the iframe element above is parsed into the DOM, so attaching here
// -- instead of waiting for window "load" -- reliably arrives before the
// iframe's own "load" event fires.
const iframe = document.getElementById("mqIframe");
const token = localStorage.getItem("selfcareJWT");

console.log("Iframe:", iframe);
console.log("JWT:", token);

function sendToken() {
    if (!token || !iframe || !iframe.contentWindow) return;
    try {
        iframe.contentWindow.postMessage(
            {
                type: "SET_JWT",
                token: token
            },
            subscribeURLS.selfcareOrigin
        );
        console.log("JWT sent to iframe");
    } catch (e) {
        console.error("Failed to send token:", e);
    }
}

// Progressive transmission on load to capture the iframe as soon as its scripts mount
iframe.addEventListener("load", function() {
    sendToken();
    setTimeout(sendToken, 200);
    setTimeout(sendToken, 500);
    setTimeout(sendToken, 1000);
    setTimeout(sendToken, 2000);
    setTimeout(sendToken, 3000);
});

// Immediate attempt in case load has already occurred synchronously
sendToken();
setTimeout(sendToken, 500);


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