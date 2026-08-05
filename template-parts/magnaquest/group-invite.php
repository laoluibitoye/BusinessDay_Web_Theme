<?php
/**
 * Group Invite page (for group owners to send/manage invites via a Leaky Paywall
 * shortcode placed in this WP page's content in wp-admin -- this template only
 * provides the styled wrapper and renders the_content() so that shortcode, and
 * anything WordPress does with it (do_shortcode, etc.), works exactly as it would
 * on any other page.
 */

// If user is not logged in, redirect them to login
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

// Group members have no group of their own to invite anyone to -- only a group
// owner (a regular Magnaquest-backed subscriber) uses this page. Mirrors the same
// guard used on my-account.php/register.php and the nav-item hiding in header.php.
if (get_user_meta(get_current_user_id(), '_bd_is_group_member', true)) {
    wp_redirect(home_url('/'));
    exit;
}
?>
<style>
.mq-auth-container {
    max-width: 600px;
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
</style>

<div class="mq-auth-container">
    <h2 class="mq-auth-title">Invite Group Members</h2>
    <p class="mq-auth-subtitle">Manage seats and send invitations for your group subscription.</p>

    <?php the_content(); ?>
</div>
