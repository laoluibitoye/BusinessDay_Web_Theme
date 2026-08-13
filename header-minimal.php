<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    // Deliberately not header.php: this is the "Minimal (No Nav)" page
    // template's own header (templates/template-minimal.php) — no ad
    // network preconnects/scripts, no analytics preload, no jQuery/legacy
    // script bundle, none of the ~900-line inline masthead stylesheet.
    // wp_head() is still required — it's what enqueues the AeroPaywall
    // SDK script and its aeroPaywallContext (class-sdk-loader.php);
    // skipping it would silently break every account/login/signup page.
    wp_head();
    ?>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #111827;
            font: 15px/1.5 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .aero-minimal-header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .aero-minimal-header a {
            display: inline-flex;
        }
        .aero-minimal-header img {
            height: 32px;
            width: auto;
        }
        .aero-minimal-main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 16px 64px;
            box-sizing: border-box;
        }
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="aero-minimal-header">
    <a href="<?= esc_url(get_site_url()) ?>" aria-label="<?= esc_attr(get_bloginfo('name')) ?>">
        <img src="<?= esc_url(get_template_directory_uri()) ?>/assets/build/images/businessday.png" alt="<?= esc_attr(get_bloginfo('name')) ?>">
    </a>
</header>

<main class="aero-minimal-main">
