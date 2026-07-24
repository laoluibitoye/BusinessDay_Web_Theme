<?php
/**
 * Magnaquest Password Reset Page
 */
?>
<style>
.mq-auth-container {
    max-width: 400px;
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
.mq-auth-footer {
    text-align: center;
    margin-top: 24px;
    font-size: 14px;
    color: #64748b;
}
.mq-auth-footer a {
    color: #E63946;
    text-decoration: none;
    font-weight: 600;
}
.mq-auth-footer a:hover {
    text-decoration: underline;
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
    <?php if (isset($_GET['token'])): ?>
        <h2 class="mq-auth-title">Choose a new password</h2>
        
        <div id="mq-reset-alert" class="mq-alert"></div>

        <form id="mq-reset-password-form">
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
            
            <input type="hidden" name="token" value="<?php echo esc_attr($_GET['token']); ?>">
            <input type="hidden" name="action" value="mq_confirm_reset_password">
            <input type="hidden" name="security" value="<?php echo wp_create_nonce('mq_auth_nonce'); ?>">
            
            <button type="submit" class="mq-submit-btn" id="mq-reset-btn">Reset Password</button>
        </form>
    <?php else: ?>
        <h2 class="mq-auth-title">Reset your password</h2>
        
        <p style="text-align: center; color: #64748b; font-size: 14px; margin-bottom: 24px; line-height: 1.5;">
            Enter the email address associated with your account and we will send you a link to reset your password.
        </p>
        
        <div id="mq-forgot-alert" class="mq-alert"></div>

        <form id="mq-forgot-password-form">
            <div class="mq-form-group">
                <label class="mq-form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="mq-form-input" required placeholder="you@example.com">
            </div>
            
            <input type="hidden" name="action" value="mq_request_reset_password">
            <input type="hidden" name="security" value="<?php echo wp_create_nonce('mq_auth_nonce'); ?>">
            
            <button type="submit" class="mq-submit-btn" id="mq-forgot-btn">Send Reset Link</button>
        </form>
    <?php endif; ?>

    <div class="mq-auth-footer">
        Back to <a href="/login">Log In</a>
    </div>
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

    // 1. Handle Forgot Password Request Form
    const forgotForm = document.getElementById('mq-forgot-password-form');
    if (forgotForm) {
        const alertBox = document.getElementById('mq-forgot-alert');
        const btn = document.getElementById('mq-forgot-btn');

        forgotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            alertBox.className = 'mq-alert';
            alertBox.innerHTML = '';
            btn.classList.add('loading');
            btn.innerHTML = 'Sending reset link...';

            const formData = new FormData(forgotForm);

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.classList.remove('loading');
                btn.innerHTML = 'Send Reset Link';

                if (data.success) {
                    alertBox.className = 'mq-alert success';
                    alertBox.innerHTML = data.data.message;
                    forgotForm.reset();
		    //MQ Code Start
		    // Redirect to login page after 2 seconds
                    setTimeout(() => {
                    	window.location.href = 'https://stg18326.businessday.ng/login/';
        	    }, 2000);
		    //MQ Code End
                } else {
                    alertBox.className = 'mq-alert error';
                    alertBox.innerHTML = data.data.message;
                }
            })
            .catch(error => {
                btn.classList.remove('loading');
                btn.innerHTML = 'Send Reset Link';
                alertBox.className = 'mq-alert error';
                alertBox.innerHTML = 'A network error occurred. Please try again.';
            });
        });
    }

    // 2. Handle Confirm Reset Password Form
    const resetForm = document.getElementById('mq-reset-password-form');
    if (resetForm) {
        const alertBox = document.getElementById('mq-reset-alert');
        const btn = document.getElementById('mq-reset-btn');

        resetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alertBox.className = 'mq-alert error';
                alertBox.innerHTML = 'Passwords do not match.';
                return;
            }

            alertBox.className = 'mq-alert';
            alertBox.innerHTML = '';
            btn.classList.add('loading');
            btn.innerHTML = 'Resetting password...';

            const formData = new FormData(resetForm);

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.classList.remove('loading');
                btn.innerHTML = 'Reset Password';

                if (data.success) {
                    alertBox.className = 'mq-alert success';
                    alertBox.innerHTML = data.data.message;
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    alertBox.className = 'mq-alert error';
                    alertBox.innerHTML = data.data.message;
                }
            })
            .catch(error => {
                btn.classList.remove('loading');
                btn.innerHTML = 'Reset Password';
                alertBox.className = 'mq-alert error';
                alertBox.innerHTML = 'A network error occurred. Please try again.';
            });
        });
    }
});
</script>