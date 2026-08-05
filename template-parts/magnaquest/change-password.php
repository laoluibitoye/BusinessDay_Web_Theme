<?php
/**
 * Change Password Form (For logged-in users)
 *
 * Shared by both group owners (Magnaquest-backed) and group members (WordPress +
 * Leaky Paywall only) -- the form/UI is identical, only the AJAX action it posts to
 * differs, since group members have no Magnaquest record for handle_mq_change_password()
 * to update. See handle_group_change_password() in functions/magnaquest-api.php.
 */

// If user is not logged in, redirect them to login
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

$is_group_member = get_user_meta(get_current_user_id(), '_bd_is_group_member', true);
$change_password_action = $is_group_member ? 'group_change_password' : 'mq_change_password';
?>
<style>
.mq-auth-container {
    max-width: 450px;
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
    margin-bottom: 10px;
}
.mq-auth-subtitle {
    text-align: center;
    color: #64748b;
    font-size: 14px;
    margin-bottom: 30px;
    line-height: 1.5;
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
.mq-submit-btn {
    width: 100%;
    padding: 14px;
    background: #E63946; /* BusinessDay Red */
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
.mq-password-wrapper {
    position: relative;
    width: 100%;
}
.mq-password-wrapper .mq-form-input {
    padding-right: 45px;
}
.mq-password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
    z-index: 10;
}
.mq-password-toggle:hover {
    color: #E63946;
}
</style>

<div class="mq-auth-container">
    <h2 class="mq-auth-title">Change Password</h2>
    <p class="mq-auth-subtitle">Update your password to keep your subscriber account secure.</p>
    
    <div id="mq-change-alert" class="mq-alert"></div>

    <form id="mq-change-password-form">
        <div class="mq-form-group">
            <label class="mq-form-label" for="old_password">Current Password</label>
            <div class="mq-password-wrapper">
                <input type="password" id="old_password" name="old_password" class="mq-form-input" required placeholder="••••••••">
                <button type="button" class="mq-password-toggle" aria-label="Toggle Password Visibility">
                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mq-form-group">
            <label class="mq-form-label" for="new_password">New Password</label>
            <div class="mq-password-wrapper">
                <input type="password" id="new_password" name="new_password" class="mq-form-input" required placeholder="••••••••" minlength="8">
                <button type="button" class="mq-password-toggle" aria-label="Toggle Password Visibility">
                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mq-form-group">
            <label class="mq-form-label" for="confirm_password">Confirm New Password</label>
            <div class="mq-password-wrapper">
                <input type="password" id="confirm_password" name="confirm_password" class="mq-form-input" required placeholder="••••••••" minlength="8">
                <button type="button" class="mq-password-toggle" aria-label="Toggle Password Visibility">
                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
        </div>
        
        <input type="hidden" name="action" value="<?php echo esc_attr($change_password_action); ?>">
        <input type="hidden" name="security" value="<?php echo wp_create_nonce('mq_auth_nonce'); ?>">
        
        <button type="submit" class="mq-submit-btn" id="mq-change-btn">Update Password</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password show/hide visibility toggle
    document.querySelectorAll('.mq-password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.mq-password-wrapper').querySelector('.mq-form-input');
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeSlashIcon = this.querySelector('.eye-slash-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeSlashIcon.style.display = 'block';
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeSlashIcon.style.display = 'none';
            }
        });
    });

    const changeForm = document.getElementById('mq-change-password-form');
    const alertBox = document.getElementById('mq-change-alert');
    const btn = document.getElementById('mq-change-btn');

    changeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            alertBox.className = 'mq-alert error';
            alertBox.innerHTML = 'New passwords do not match.';
            return;
        }

        alertBox.className = 'mq-alert';
        alertBox.innerHTML = '';
        btn.classList.add('loading');
        btn.innerHTML = 'Updating password...';

        const formData = new FormData(changeForm);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.classList.remove('loading');
            btn.innerHTML = 'Update Password';

            if (data.success) {
                if (data.data.tokenObject) {
                    localStorage.setItem('selfcareJWT', JSON.stringify(data.data.tokenObject));
                }
                alertBox.className = 'mq-alert success';
                alertBox.innerHTML = data.data.message;
                changeForm.reset();
                setTimeout(() => {
                    // group_change_password (functions/magnaquest-api.php) returns a "redirect"
                    // field pointing group members home instead of /my-account, which they
                    // have no access to (see the guard in my-account.php).
                    window.location.href = data.data.redirect || '/my-account';
                }, 2000);
            } else {
                alertBox.className = 'mq-alert error';
                alertBox.innerHTML = data.data.message;
            }
        })
        .catch(error => {
            btn.classList.remove('loading');
            btn.innerHTML = 'Update Password';
            alertBox.className = 'mq-alert error';
            alertBox.innerHTML = 'A network error occurred. Please try again.';
        });
    });
});
</script>
