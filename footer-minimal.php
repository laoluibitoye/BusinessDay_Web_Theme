<?php
/**
 * Pairs with header-minimal.php / templates/template-minimal.php. Not a
 * stripped-down copy of footer.php — that file's dark, multi-column
 * sitemap footer only renders correctly because header.php's ~900-line
 * inline stylesheet happens to also contain this theme's only copy of
 * Bootstrap-equivalent utility classes (.row, .col-md-N, .bg-dark,
 * .text-white, .py-3, etc. — there is no actual Bootstrap CSS file loaded
 * anywhere in this theme). header-minimal.php deliberately doesn't load
 * that stylesheet, so footer.php's markup was rendering completely
 * unstyled on every minimal-template page. This is a real, small,
 * self-contained footer instead — no dependency on any other file's CSS.
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
    <style>
        .aero-minimal-footer {
            margin-top: 48px;
            padding: 24px 16px 32px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font: 13px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            color: #6b7280;
        }
        .aero-minimal-footer a {
            color: #6b7280;
            text-decoration: underline;
        }
        .aero-minimal-footer a:hover {
            color: #111827;
        }
        .aero-minimal-footer nav {
            margin-bottom: 8px;
        }
        .aero-minimal-footer nav a {
            margin: 0 8px;
        }
    </style>
    <footer class="aero-minimal-footer">
        <nav>
            <a href="https://businessday.ng/app-privacy-policy/">Privacy Policy</a>
            <a href="https://businessday.ng/copyright/">Copyright</a>
            <a href="<?= esc_url(get_site_url()) ?>">Home</a>
        </nav>
        <p>&copy; <?= esc_html(date('Y')) ?> BusinessDay Media Ltd.</p>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
