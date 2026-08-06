<?php
/**
 * Sidebar Google News Card Component
 */
?>
<style>
    .google-news-card-widget {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px 20px;
        margin-top: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        text-align: left;
        width: 100%;
        box-sizing: border-box;
    }
    .google-news-card-widget .gn-card-header {
        margin-bottom: 16px;
    }
    .google-news-card-widget .gn-card-logo {
        max-width: 100%;
        width: auto;
        max-height: 46px;
        display: block;
        border-radius: 4px;
    }
    .google-news-card-widget .gn-card-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 12px 0;
        line-height: 1.25;
    }
    .google-news-card-widget .gn-card-text {
        font-size: 15px;
        line-height: 1.45;
        color: #374151;
        margin: 0 0 20px 0;
        font-weight: 400;
    }
    .google-news-card-widget .gn-card-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 50px;
        padding: 10px 16px;
        width: 100%;
        box-sizing: border-box;
        text-decoration: none !important;
        transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    .google-news-card-widget .gn-card-button:hover {
        background-color: #f9fafb;
        border-color: #9ca3af;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .google-news-card-widget .gn-card-button span {
        font-size: 14px;
        font-weight: 700;
        color: #b91c1c;
    }
</style>

<div class="google-news-card-widget">
    <div class="gn-card-header">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/businessday-banner-logo.jpg" alt="BusinessDay Logo" class="gn-card-logo">
    </div>
    <h3 class="gn-card-title">Stay Ahead with BusinessDay</h3>
    <p class="gn-card-text">Follow us on Google News and never miss breaking stories, market insights, and in-depth reporting.</p>
    <a href="https://news.google.com/publications/CAAqKQgKIiNDQklTRkFnTWFoQUtEbUoxYzJsdVpYTnpaR0Y1TG01bktBQVAB?hl=en-NG&gl=NG&ceid=NG%3Aen" target="_blank" rel="noopener noreferrer" class="gn-card-button">
        <svg class="gn-g-logo" viewBox="0 0 24 24" width="22" height="22">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Follow us on Google News</span>
    </a>
</div>
