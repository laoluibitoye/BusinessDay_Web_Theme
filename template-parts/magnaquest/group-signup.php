<?php
/**
 * Group Member Signup / Login page.
 *
 * Opened from a Leaky Paywall group-invite email (see the wp_mail filter in functions.php
 * that rewrites lp_group_invite_key links to /group-signup/?invite_key=...). The email is
 * prefilled from the invite and locked; the same form handles both "create your password"
 * (new WordPress account) and "log in" (account already exists) since the server
 * (handle_group_signup() in functions/magnaquest-api.php) determines which case applies.
 * This never calls any Magnaquest API -- see that function's docblock for why.
 */
?>
<style>
.mq-auth-container {
    max-width: 440px;
    margin: 60px auto;
    padding: 40px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-family: 'Inter', sans-serif;
}
.mq-auth-title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}
.mq-auth-subtitle {
    text-align: center;
    font-size: 14px;
    color: #64748b;
    margin-bottom: 30px;
}
.mq-form-group {
    margin-bottom: 20px;
}
.mq-form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #4a4a4a;
    margin-bottom: 8px;
}
.mq-form-input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.2s ease;
    background: #f8fafc;
}
.mq-form-input:focus {
    outline: none;
    border-color: #E63946;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
}
.mq-form-input[readonly] {
    background: #eef1f5;
    color: #64748b;
    cursor: not-allowed;
}
.mq-submit-btn {
    width: 100%;
    padding: 14px;
    background: #E63946;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.1s ease;
    margin-top: 10px;
}
.mq-submit-btn:hover {
    background: #D62828;
}
.mq-submit-btn:active {
    transform: scale(0.98);
}
.mq-submit-btn.loading {
    opacity: 0.7;
    pointer-events: none;
}
.mq-alert {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    display: none;
}
.mq-alert.error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
    display: block;
}
.mq-alert.success {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    display: block;
}
</style>

<div class="mq-auth-container">
    <h2 class="mq-auth-title">Activate Your Group Membership</h2>
    <p class="mq-auth-subtitle">Enter a password to finish setting up your account, or your existing password if you already have one.</p>

    <div id="mq-group-signup-alert" class="mq-alert"></div>

    <form id="mq-group-signup-form">
        <div class="mq-form-group">
            <label class="mq-form-label" for="group_signup_email">Email Address</label>
            <input type="email" id="group_signup_email" class="mq-form-input" readonly>
        </div>

        <div class="mq-form-group">
            <label class="mq-form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="mq-form-input" required placeholder="••••••••">
        </div>

        <input type="hidden" name="action" value="handle_group_signup">
        <input type="hidden" name="security" value="<?php echo wp_create_nonce('mq_auth_nonce'); ?>">
        <input type="hidden" name="invite_key" id="invite_key" value="">

        <button type="submit" class="mq-submit-btn" id="mq-group-signup-btn">Activate</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prefill the (locked) email from the invite -- adapted from the working
    // populateGroupInviteDetails() in sign-up.php (its second, broken duplicate further
    // down that file is unrelated and left untouched).
    const urlParams = new URLSearchParams(window.location.search);
    const inviteKey = urlParams.get('invite_key');
    const form = document.getElementById('mq-group-signup-form');
    const alertBox = document.getElementById('mq-group-signup-alert');
    const btn = document.getElementById('mq-group-signup-btn');
    const emailField = document.getElementById('group_signup_email');
    const inviteKeyField = document.getElementById('invite_key');

    if (!inviteKey) {
        alertBox.className = 'mq-alert error';
        alertBox.innerHTML = 'This link is missing its invite key. Please use the link from your invitation email.';
        btn.disabled = true;
        return;
    }

    inviteKeyField.value = inviteKey;

    fetch('/wp-json/businessday/v1/group-invite?invite_key=' + encodeURIComponent(inviteKey))
        .then(response => response.json())
        .then(data => {
            if (!data.success || data.status !== 'pending') {
                alertBox.className = 'mq-alert error';
                alertBox.innerHTML = 'This invite link is invalid or has already been used.';
                btn.disabled = true;
                return;
            }
            emailField.value = data.email;
        })
        .catch(function() {
            alertBox.className = 'mq-alert error';
            alertBox.innerHTML = 'Unable to verify this invite link right now. Please try again shortly.';
            btn.disabled = true;
        });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        alertBox.className = 'mq-alert';
        alertBox.innerHTML = '';

        btn.classList.add('loading');
        btn.innerHTML = 'Activating...';

        const formData = new FormData(form);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.classList.remove('loading');
            btn.innerHTML = 'Activate';

            if (data.success) {
                alertBox.className = 'mq-alert success';
                alertBox.innerHTML = data.data.message;
                setTimeout(() => {
                    window.location.href = data.data.redirect;
                }, 1000);
            } else {
                alertBox.className = 'mq-alert error';
                alertBox.innerHTML = data.data.message;
            }
        })
        .catch(function() {
            btn.classList.remove('loading');
            btn.innerHTML = 'Activate';
            alertBox.className = 'mq-alert error';
            alertBox.innerHTML = 'A network error occurred. Please try again.';
        });
    });
});
</script>
