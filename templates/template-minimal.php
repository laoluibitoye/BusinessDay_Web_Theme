<?php
/**
 * Template Name: Minimal (No Nav)
 *
 * Purpose-built for pages that shouldn't inherit the site's sticky
 * masthead, search/hamburger, or the article-specific chrome page.php
 * wraps every page in by default (breadcrumb, get_social_share_icons()).
 * First used by the Account/Log In/Create Account pages (see AeroPaywall's
 * [aeropaywall_account] shortcode) — reusable for any future sales/
 * landing/funnel page too, since it makes no width/layout assumptions
 * about the content itself.
 *
 * Pairs with header-minimal.php, which opens <main class="aero-minimal-
 * main"> without closing it — this file closes it, same "content wrapper
 * spans header→template, template closes it before get_footer()" shape
 * page.php's own #article-page wrapper already uses in this theme.
 *
 * get_footer('minimal') loads footer-minimal.php, not the theme's normal
 * footer.php — that file's sitemap styling depends entirely on
 * header.php's own inline stylesheet (see footer-minimal.php's docblock),
 * which this template never loads.
 */

get_header('minimal');
if (have_posts()) :
    the_post();
    the_content();
endif;
?>
</main>
<?php
get_footer('minimal');
