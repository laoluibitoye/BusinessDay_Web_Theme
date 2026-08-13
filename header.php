<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php
// Determine if programmatic ads should be shown
$show_programmatic_ads = true;

// Disable all programmatic ad networks whenever the active Theme Environment
// (Settings -> Theme Environment) is Staging -- ad networks should only ever serve
// impressions on the live/production environment. See bd_get_theme_environment()
// in functions/features.php.
if ( bd_get_theme_environment() !== 'live' ) {
    $show_programmatic_ads = false;
}
?>

<head>
    <?php if ( ! $show_programmatic_ads ) : ?>
    <!-- DEBUG: PROGRAMMATIC ADS DISABLED FOR SUBSCRIBER -->
    <style>
        /* Forcefully hide programmatic ad containers for subscribers (Google, AdSense Auto Ads, Dochase, Adsolut) */
        div[id^="div-gpt-ad-"],
        div[id^="google_ads_"],
        iframe[id^="google_ads_iframe_"],
        iframe[name^="google_ads_iframe"],
        ins.adsbygoogle,
        [class*="adsolut"],
        [id*="adsolut"],
        .adsolut-player,
        .adsolut-container,
        .OUTBRAIN,
        [id^="taboola-"] {
            display: none !important;
            height: 0 !important;
            width: 0 !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            position: absolute !important;
            z-index: -9999 !important;
        }
    </style>
    <script>
        // Tell any custom scripts that ads are disabled
        window.DISABLE_PROGRAMMATIC_ADS = true;

        // Dummy googletag object to prevent JS errors when ads are disabled
        window.googletag = {
            cmd: [],
            enums: {
                OutOfPageFormat: {
                    TOP_ANCHOR: 'TOP_ANCHOR',
                    BOTTOM_ANCHOR: 'BOTTOM_ANCHOR',
                    LEFT_SIDE_RAIL: 'LEFT_SIDE_RAIL',
                    RIGHT_SIDE_RAIL: 'RIGHT_SIDE_RAIL',
                    INTERSTITIAL: 'INTERSTITIAL'
                }
            },
            defineSlot: function() { return this; },
            defineOutOfPageSlot: function() { return this; },
            addService: function() { return this; },
            pubads: function() {
                return {
                    enableSingleRequest: function() {},
                    enableLazyLoad: function() {},
                    collapseEmptyDivs: function() {},
                    setTargeting: function() {},
                    refresh: function() {},
                    addEventListener: function() {}
                };
            },
            enableServices: function() {},
            display: function() {},
            sizeMapping: function() {
                return {
                    addSize: function() { return this; },
                    build: function() { return this; }
                };
            }
        };
        window.googletag.cmd.push = function(cb) {
            // Do not execute callbacks to block ads from loading
        };
    </script>
    <?php else: ?>
    <!-- DEBUG: PROGRAMMATIC ADS ENABLED -->
    <?php endif; ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://tpc.googlesyndication.com">
    <link rel="preconnect" href="https://securepubads.g.doubleclick.net">
    <link rel="preconnect" href="https://pagead2.googlesyndication.com">
    <link rel="preconnect" href="https://fundingchoicesmessages.google.com">


    <link rel="dns-prefetch" href="https://www.googletagmanager.com/">

    <link href="https://www.googletagmanager.com/gtag/js?id=G-KRZW6E45JP" rel="preload" as="script">

    <meta name="db301b720d4f9408699cce1e2b057636ca123d36" content="db301b720d4f9408699cce1e2b057636ca123d36" />

    <?php wp_head(); ?>

    <script>
        <?php include('assets/jquery.php'); ?>
        <?php include('assets/build/js/script.php'); ?>
    </script>

    <style>
        <?php echo require_once('assets/build/css/style.php'); ?>
    </style>
    <style>
        <?php echo require_once('assets/build/css/responsive.php'); ?>
    </style>
    <?php
    $typography = get_option('bday_typography_meta');
    if (is_array($typography) && !empty($typography)) {
        $header_font = $typography['header_font_family'] ?? '';
        $header_weights = $typography['header_font_weights'] ?? '';
        $body_font = $typography['body_font_family'] ?? '';
        $body_weights = $typography['body_font_weights'] ?? '';
        $post_title_size = $typography['post_title_size'] ?? '';
        $article_size = $typography['article_size'] ?? '';
        $link_color = $typography['link_color'] ?? '';
        $header_line_height = $typography['header_line_height'] ?? '';
        $header_letter_spacing = $typography['header_letter_spacing'] ?? '';
        $body_line_height = $typography['body_line_height'] ?? '';
        $body_letter_spacing = $typography['body_letter_spacing'] ?? '';

        // Load Google Fonts (using v1 syntax for easier weight parsing)
        $fonts_to_load = [];
        if (!empty($header_font)) {
            $fonts_to_load[] = $header_font . ($header_weights ? ':' . $header_weights : '');
        }
        if (!empty($body_font)) {
            $fonts_to_load[] = $body_font . ($body_weights ? ':' . $body_weights : '');
        }

        if (!empty($fonts_to_load)) {
            $google_fonts_url = "https://fonts.googleapis.com/css?family=" . implode('|', array_map('urlencode', $fonts_to_load)) . "&display=swap";
            echo "<link href='" . esc_url($google_fonts_url) . "' rel='stylesheet'>\n";
        }

        echo "<style>\n";
        if (!empty($header_font)) {
            echo "h1, h2, h3, h4, h5, h6, .post-title, .post-title a { font-family: '" . esc_html($header_font) . "', sans-serif !important; }\n";
        }
        if (!empty($body_font)) {
            echo "body, p, .article-text, .post-excerpt, article p, .edition-info p { font-family: '" . esc_html($body_font) . "', sans-serif !important; }\n";
        }
        
        if (!empty($post_title_size) || !empty($header_line_height) || !empty($header_letter_spacing)) {
            echo ".post-title, .post-title a {\n";
            if (!empty($post_title_size)) echo "  font-size: " . esc_html($post_title_size) . " !important;\n";
            if (!empty($header_line_height)) echo "  line-height: " . esc_html($header_line_height) . " !important;\n";
            if (!empty($header_letter_spacing)) echo "  letter-spacing: " . esc_html($header_letter_spacing) . " !important;\n";
            echo "}\n";
        }

        if (!empty($article_size) || !empty($body_line_height) || !empty($body_letter_spacing)) {
            echo ".post-content p, .article-text, .post-excerpt, article p, .edition-info p {\n";
            if (!empty($article_size)) echo "  font-size: " . esc_html($article_size) . " !important;\n";
            if (!empty($body_line_height)) echo "  line-height: " . esc_html($body_line_height) . " !important;\n";
            if (!empty($body_letter_spacing)) echo "  letter-spacing: " . esc_html($body_letter_spacing) . " !important;\n";
            echo "}\n";
        }

        if (!empty($link_color)) {
            echo "a { color: " . esc_html($link_color) . " !important; }\n";
        }

        echo "</style>\n";
    }
    ?>
    <?php if (is_front_page()):
        // echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
    ?>
        <script>
            $(document).ready(function() {
                // General carousel initialization (excluding Bloomberg)
                const $generalCarousel = $(".owl-carousel:not(.bloomberg-owl)");

                $generalCarousel.owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 2 },
                        1000: { items: 4.5 }
                    },
                    slideBy: 4,
                });

                // Bloomberg specific carousel
                $(".bloomberg-owl").owlCarousel({
                    loop: false,
                    margin: 20,
                    nav: true,
                    dots: true,
                    slideBy: 1,
                    smartSpeed: 400,
                    navText: ["&lsaquo;", "&rsaquo;"],
                    responsive: {
                        0: { items: 1.1, margin: 10 },
                        600: { items: 2.2, margin: 15 },
                        1000: { items: 4.2, margin: 20 }
                    }
                });

                // Scoped event listener for all carousels
                $(".owl-carousel").on("changed.owl.carousel", function(event) {
                    const $current = $(event.target);
                    const total = event.item.count;
                    const visible = event.page.size;
                    const current = event.item.index;

                    // Update navigation buttons for this specific carousel
                    $current.find(".owl-next").toggleClass("disabled", current + visible >= total).prop("disabled", current + visible >= total);
                    $current.find(".owl-prev").toggleClass("disabled", current <= 0).prop("disabled", current <= 0);

                    // Special transform logic only for general carousels near the end
                    if (!$current.hasClass('bloomberg-owl') && current + 4 >= total) {
                        const offset = (total - visible) * (100 / visible);
                        $current.find(".owl-stage").css("transform", "translate3d(-" + offset + "%, 0, 0)");
                    }
                });
            });
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
        <style>
            @media only screen and (max-width: 769px) {
                .other-news-section .news-type-3 {
                    display: block;
                }
            }

            .news-type-3>article {
                margin-top: 10px !important;
            }

            @media screen and (max-width: 531px) {
                .main {
                    order: 1;
                }

                .recent {
                    order: 4;
                }

                .top_stories {
                    order: 3;
                }

                .space {
                    order: 2;
                }
            }

            .post-title {
                font-weight: 700 !important;
                font-size: 19px !important;
            }

            .owl-carousel .item {
                flex-shrink: 0;
            }

            .owl-nav button {
                background: #000;
                color: #fff;
                border: none;
                padding: 10px;
                border-radius: 50%;
            }

            .owl-theme .owl-nav {
                position: absolute;
                top: -70px;
                right: 0;
            }

            .owl-carousel .owl-nav button.owl-next,
            .owl-carousel .owl-nav button.owl-prev {
                font-size: 2.5rem;
                vertical-align: middle;
            }

            .owl-carousel .owl-nav button.owl-next:hover,
            .owl-carousel .owl-nav button.owl-prev:hover {
                background-color: transparent;
            }

            .owl-theme .owl-dots .owl-dot {
                display: none;
            }

            .card-title-18 {
                font-size: 18px;
                font-weight: 500;
            }

            .card-text-14 {
                font-size: 14px;
                font-weight: 400px;
            }

            .play-icon {
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
                border-left: 20px solid white;
            }

            .owl-carousel .owl-stage-outer {
                height: 400px;
                /* width: 300px; */
            }

            .owl-theme .owl-nav {
                top: -60px !important;
                font-size: 2rem !important;
            }

            .card-title-18 {
                height: 145px;
            }
        </style>

    <?php endif; ?>

    <?php

    $page_section_v = $page_author_v = "";
    if (is_front_page()) {
        $page_section_v = 'homepage';
    } else if (is_single()) {

        $categories = get_the_category();
        // $categories = wp_get_post_categories($post->ID, array( 'fields' => 'slugs' ) );
        // echo 'page_x'.json_encode($categories);
        $page_author_v = get_the_author_meta('display_name', get_post_field('post_author', $post->ID));
        $page_section_v =  (! empty($categories)) ? $categories[0]->name : '';
    } else if (is_archive()) {
        $page_section_v = get_the_archive_title();
    }

    ?>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KRZW6E45JP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-KRZW6E45JP');
    </script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BS5YSBR9FP"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-BS5YSBR9FP');
    </script>

    <script type='text/javascript'>
        (function() {
            /** CONFIGURATION START **/
            var _sf_async_config = window._sf_async_config = (window._sf_async_config || {});
            _sf_async_config.uid = 67124;
            _sf_async_config.domain = 'businessday.ng';
            _sf_async_config.useCanonical = true;
            _sf_async_config.useCanonicalDomain = true;
            _sf_async_config.sections = '<?= $page_section_v ?>'; //SET PAGE SECTION(S)
            _sf_async_config.authors = '<?= $page_author_v ?>'; //SET PAGE AUTHOR(S)
            /** CONFIGURATION END **/
            function loadChartbeat() {
                var e = document.createElement('script');
                var n = document.getElementsByTagName('script')[0];
                e.type = 'text/javascript';
                e.async = true;
                e.src = '//static.chartbeat.com/js/chartbeat.js';
                n.parentNode.insertBefore(e, n);
            }
            loadChartbeat();
        })();
    </script>
    <script async src="//static.chartbeat.com/js/chartbeat_mab.js"></script>

        <script type="text/javascript" async src="//l.getsitecontrol.com/m42y997y.js"></script>

    <script type="text/javascript">
        ! function() {
            "use strict";
            var o = window.jstag || (window.jstag = {}),
                r = [];

            function n(e) {
                o[e] = function() {
                    for (var n = arguments.length, t = new Array(n), i = 0; i < n; i++) t[i] = arguments[i];
                    r.push([e, t])
                }
            }
            n("send"), n("mock"), n("identify"), n("pageView"), n("unblock"), n("getid"), n("setid"), n("loadEntity"), n("getEntity"), n("on"), n("once"), n("call"), o.loadScript = function(n, t, i) {
                var e = document.createElement("script");
                e.async = !0, e.src = n, e.onload = t, e.onerror = i;
                var o = document.getElementsByTagName("script")[0],
                    r = o && o.parentNode || document.head || document.body,
                    c = o || r.lastChild;
                return null != c ? r.insertBefore(e, c) : r.appendChild(e), this
            }, o.init = function n(t) {
                return this.config = t, this.loadScript(t.src, function() {
                    if (o.init === n) throw new Error("Load error!");
                    o.init(o.config),
                        function() {
                            for (var n = 0; n < r.length; n++) {
                                var t = r[n][0],
                                     i = r[n][1];
                                o[t].apply(o, i)
                            }
                            r = void 0
                        }()
                }), this
            }
        }();
        // Define config and initialize Lytics tracking tag.
        // - The setup below will disable the automatic sending of Page Analysis Information (to prevent duplicative sends, as this same information will be included in the jstag.pageView() call below, by default)
        jstag.init({
            src: 'https://c.lytics.io/api/tag/83288ca484b4febdd7907bd820c502cd/latest.min.js',
            pageAnalysis: {
                dataLayerPull: {
                    disabled: true
                }
            }
        });

        // You may need to send a page view, depending on your use-case
        jstag.pageView();
    </script>

    <?php // Already loaded and checked above ?>

    <!--dochase-->
    <!--
      REVERTED (2026-07-21): swapped back to the exact registerSlot() calls
      from New File Updates/header.php at the user's request. Note this
      reintroduces the missing-closing-bracket syntax error on 4 of the 5
      calls below (top, top2, body1, body3 — body2 was already correct in
      the source) that the 2026-07-18 fix had corrected; `node --check`
      fails on this block again as a result, which will likely stop every
      slot in this script (top/top2/body1/body2/body3/anchor/interstitial)
      from rendering. The staging is_live PHP gate that used to wrap this
      block has also been removed (not present in the original file) — ads
      from this block now fire unconditionally on every environment,
      staging included.
    -->
    <?php if ( $show_programmatic_ads ) : ?>
            <script id="gpt-ad-tag" async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js" crossorigin="anonymous"></script>
<script>
window.googletag = window.googletag || { cmd: [] };
googletag.cmd.push(function () {

  /* =========================
     CORE STATE
  ========================== */
  var slots = [];
  var slotState = {}; // per-slot intelligence

  var MAX_REFRESHES = 3; // strict AdX-safe cap

  /* =========================
     ACTIVITY TRACKING
  ========================== */
  var lastActivity = Date.now();

  ['mousemove', 'scroll', 'touchstart', 'keydown'].forEach(function (evt) {
    document.addEventListener(evt, function () {
      lastActivity = Date.now();
    }, { passive: true });
  });

  /* =========================
     SIZE MAPPINGS
  ========================== */

  var mappingTop = googletag.sizeMapping()
    .addSize([1024, 0], [[970, 90], [728, 90], 'fluid'])
    .addSize([768, 0], [[728, 90], [300, 250], [300, 100], 'fluid'])
    .addSize([0, 0], [[320, 100], [320, 50], [300, 250], 'fluid'])
    .build();

  var mappingBody = googletag.sizeMapping()
    .addSize([1024, 0], [[336, 280], [300, 280], [300, 250], [250, 250], [320, 480], [480, 320], [320, 100], [320, 50], [300, 50], 'fluid'])
    .addSize([768, 0], [[336, 280], [300, 250], [250, 250], [320, 480], [480, 320], [320, 100], [320, 50], [300, 50], 'fluid'])
    .addSize([0, 0], [[300, 250], [250, 250], [320, 480], [480, 320], [320, 100], [320, 50], [300, 50], 'fluid'])
    .build();

  /* =========================
     SLOT FACTORY
  ========================== */

  function registerSlot(path, sizes, id, mapping) {
    var slot = googletag.defineSlot(path, sizes, id);

    if (!slot) return null;

    slot.defineSizeMapping(mapping)
        .addService(googletag.pubads());

    slots.push(slot);

    slotState[id] = {
      refreshCount: 0,
      viewable: false,
      lastRefresh: 0
    };

    return slot;
  }

  /* =========================
     SLOTS
  ========================== */

  registerSlot('/23043164651,21781351181/businessday_top',
    [[728, 90], [300, 50], [320, 100], [300, 100], [468, 60], [970, 90], 'fluid', [320, 50], [300, 250],
    'div-gpt-ad-1783084250687-0',
    mappingTop
  );

  registerSlot('/23043164651,21781351181/businessday_top2',
    [[300, 50], [300, 280], [320, 50], [300, 250], [728, 90], [468, 60], [970, 90], [320, 100], 'fluid', [300, 100],
    'div-gpt-ad-1783084673395-0',
    mappingTop
  );

registerSlot('/23043164651,21781351181/businessday_body1',
    [[300, 50], [300, 100], [200, 200], [250, 250], [336, 280], [300, 250], 'fluid', [320, 100], [320, 50],
    'div-gpt-ad-1783096747143-0',
    mappingBody
  );

registerSlot('/23043164651,21781351181/businessday_body2',
    [[300, 50], [728, 90], [300, 100], [320, 100], [320, 50], [250, 250], [336, 280], [300, 250], [200, 200], [320, 480], 'fluid'],
    'div-gpt-ad-1783097109737-0',
    mappingBody
  );

registerSlot('/23043164651,21781351181/businessday_body3',
    [[160, 600], [120, 600], [200, 200], [320, 480], [300, 600], 'fluid', [250, 250], [300, 250], [336, 280],
    'div-gpt-ad-1783098103568-0',
    mappingBody
  );

let anchorSlot;
let interstitialSlot;
anchorSlot = googletag.defineOutOfPageSlot('/23043164651,21781351181/businessday/businessday_anchor', googletag.enums.OutOfPageFormat.BOTTOM_ANCHOR);
interstitialSlot = googletag.defineOutOfPageSlot('/23043164651,21781351181/businessday/businessday_interstitial', googletag.enums.OutOfPageFormat.INTERSTITIAL);
if (anchorSlot) anchorSlot.addService(googletag.pubads());
if (interstitialSlot) interstitialSlot.addService(googletag.pubads());

  /* =========================
     GAM SETTINGS
  ========================== */

  googletag.pubads().enableSingleRequest();

  googletag.pubads().enableLazyLoad({
    fetchMarginPercent: 100,
    renderMarginPercent: 50,
    mobileScaling: 1.0
  });

  googletag.pubads().collapseEmptyDivs(true);

  var pageCategory = window.pageCategory || 'all';
  googletag.pubads().setTargeting('sections', [pageCategory]);

  googletag.enableServices();

  /* =========================
     VIEWABILITY-BASED ENGINE
  ========================== */

  function canRefresh(id) {
    var s = slotState[id];

    if (!s) return false;
    if (s.refreshCount >= MAX_REFRESHES) return false;

    if (document.hidden || !document.hasFocus()) return false;
    if (Date.now() - lastActivity > 180000) return false;

    // minimum cooldown between refreshes (2 minutes)
    if (Date.now() - s.lastRefresh < 120000) return false;

    return true;
  }

  function refreshSlot(slot, id) {
    if (!canRefresh(id)) return;

    googletag.pubads().refresh([slot]);

    slotState[id].refreshCount++;
    slotState[id].lastRefresh = Date.now();
  }

  /* =========================
     GPT EVENT-DRIVEN REFRESH
  ========================== */

  googletag.pubads().addEventListener('impressionViewable', function (event) {
    var slot = event.slot;
    var id = slot.getSlotElementId();

    if (!slotState[id]) return;

    slotState[id].viewable = true;

    // only refresh after a real viewable impression
    setTimeout(function () {
      refreshSlot(slot, id);
    }, 30000); // 30s post-view delay
  });

  /* =========================
     INTERSECTION OBSERVER (TRIGGER LAYER)
  ========================== */

  var observer = new IntersectionObserver(function (entries) {

    entries.forEach(function (entry) {

      if (!entry.isIntersecting || entry.intersectionRatio < 0.7) return;

      var id = entry.target.id;

      var slot = slots.find(function (s) {
        return s.getSlotElementId() === id;
      });

      if (!slot) return;

      // DO NOT refresh immediately — only mark eligible
      if (!slotState[id]) return;

      slotState[id].eligible = true;
    });

  }, {
    threshold: [0.7]
  });

  /* =========================
     ATTACH OBSERVER
  ========================== */

  googletag.cmd.push(function () {
    slots.forEach(function (slot) {
      var id = slot.getSlotElementId();
      var el = document.getElementById(id);
      if (el) observer.observe(el);
    });
  });

});
</script>
    <?php endif; ?>
    <!--dochase end-->
    <?php if (amp_is_request()): ?>

        <script async src="https://cdn.ampproject.org/v0.js"></script>
        <script async custom-element="amp-consent" src="https://cdn.ampproject.org/v0/amp-consent-0.1.js"></script>
        <script async custom-element="amp-google-document-embed" src="https://cdn.ampproject.org/v0/amp-google-document-embed-0.1.js"></script>

        <amp-analytics type="googleanalytics" config="https://amp.analytics-debugger.com/ga4.json" data-credentials="include">
            <script type="application/json">
                {
                    "vars": {
                        "GA4_MEASUREMENT_ID": "G-KRZW6E45JP",
                        "GA4_ENDPOINT_HOSTNAME": "www.google-analytics.com",
                        "DEFAULT_PAGEVIEW_ENABLED": true,
                        "GOOGLE_CONSENT_ENABLED": false,
                        "WEBVITALS_TRACKING": false,
                        "PERFORMANCE_TIMING_TRACKING": false,
                        "SEND_DOUBLECLICK_BEACON": false
                    }
                }
            </script>
        </amp-analytics>

        <amp-analytics type="parsely">
            <script type="application/json">
                {
                    "vars": {
                        "apikey": "businessday.ng"
                    }
                }
            </script>
        </amp-analytics>

        <amp-analytics type="chartbeat">
            <script type="application/json">
                {
                    "vars": {
                        "uid": "67124",
                        "domain": "businessday.ng",
                        "sections": "<?= $page_section_v ?>",
                        "authors": "<?= $page_author_v ?>"
                    }
                }
            </script>
        </amp-analytics>


        <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//data.businessday.ng/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '2']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
        </script>
        <?php endif; ?>


    <style>
        .premium_leaderboard {
            height: auto;
            width: 100%;
        }

        .mobile-only {
            display: none !important;
        }

        .margin-top {
            margin-top: 1em;
        }

        .margin-bottom {
            margin-bottom: 1em;
        }

        .ad-container {
             align-items: center;
            /* UPDATED (2026-07-18): background-color #FFF1E0 -> #F8F9FA, missed in the
               earlier design-refresh pass (masterpage.php/widgets.php already had it) */
            background-color: #F8F9FA;
            padding-top: 0.5em;
            padding-bottom: 1em;
            min-height: 250px;
        }

        .ad-container-silent {
            align-items: center;
            /* background-color: #f0f0f0; */
            /* padding-top: 0.5em; */
            /* padding-bottom: 1em; */
            /* min-height: 250px; */
        }

        .ad-container::before {
            content: 'advertisement';
            /* text-transform: uppercase; */
            font-size: 9px;
            font-style: normal;
            font-weight: 314;
            letter-spacing: 0.388em;
            line-height: 14px;
            display: flex;
            justify-content: center;
            color: #000;
            font-family: monospace;
        }

        .ad-container-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 250px;
        }

        .top-sticky {
            position: sticky;
            top: 9em;
        }

        .readmore-button {
            border: 1.5px solid #f91212;
            padding: 0.3em;
            color: grey;

        }

        a.readmore-button:hover {
            background-color: #f91212;
            color: #fff;
        }

        @media (max-width: 480px) {
            .mobile-only {
                display: block !important;
            }

            .desktop-only {
                display: none;
            }
        }



        .ring-container {
            position: relative;
        }

        .circle {
            width: 15px;
            height: 15px;
            background-color: #f91212;
            border-radius: 50%;
            position: absolute;
            top: 23px;
            left: 23px;
        }

        .circle span {
            margin-left: 1.5em;
            color: #f91212;
        }

        .ringring {
            border: 3px solid #f91212;
            -webkit-border-radius: 30px;
            height: 25px;
            width: 25px;
            position: absolute;
            left: 15px;
            top: 15px;
            -webkit-animation: pulsate 1s ease-out;
            -webkit-animation-iteration-count: infinite;
            opacity: 0.0
        }

        @-webkit-keyframes pulsate {
            0% {
                -webkit-transform: scale(0.1, 0.1);
                opacity: 0.0;
            }

            50% {
                opacity: 1.0;
            }

            100% {
                -webkit-transform: scale(1.2, 1.2);
                opacity: 0.0;
            }
        }

        .events-separator {
            background: linear-gradient(90deg, #fff1e5 2px, transparent 1%) 50%, linear-gradient(#fff1e5 2px, transparent 1%) 50%, #000;
            background-position: 0 0;
            background-size: 3px 3px;
            height: 9px;
            width: 100%;
        }

        .widget-btn {
            font-size: 11px;
            padding: 8px 12px;
            color: #fff;
            background-color: #2a2a2a;
            border-radius: 5px;
            width: 100%;
            display: block;
            text-align: center;
            margin-top: 1em;
        }

        .blinking{
            animation: 1s blink ease infinite;
            width: 10px;
            height: 10px;
            border-radius: 100%;
            float: left;
            margin-right: 10px;
            margin-top: 2px;
        }

        @-webkit-keyframes "blink" {
        0% {
            opacity: 0;
            background-color: red;
        }
        25% {
            opacity: 1;
            background-color: red;
        }
        }

        /* The account menu previously styled here (a dropdown on desktop,
           a stacked block in the mobile offcanvas — both reusing CSS left
           over from a prior, now-removed competing subscription platform)
           is gone: header.php now just calls aero_paywall_nav_button(),
           which renders a complete, self-styled flyout panel (SDK-injected
           CSS, not this file). Nothing left here to keep in sync with the
           plugin's markup contract. */
    </style>

    <?php if ( $show_programmatic_ads ) : ?>
    <script id="gpt-ad-tag" async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>

    <div class="d-none d-md-block">
        <script>
            window.googletag = window.googletag || {
                cmd: []
            };
            googletag.cmd.push(function() {
                googletag.defineSlot('/21781351181/bd_desktop_1', [
                    [970, 250], 'fluid', [468, 60],
                    [970, 90],
                    [300, 250],
                    [728, 90]
                ], 'div-gpt-ad-1731136144280-0').addService(googletag.pubads());
                googletag.pubads().enableSingleRequest();
                googletag.enableServices();
            });
        </script>

        <script>
            window.googletag = window.googletag || {
                cmd: []
            };
            googletag.cmd.push(function() {
                googletag.defineSlot('/21781351181/bd_desktop_2', [
                    [970, 250], 'fluid', [300, 250],
                    [728, 90],
                    [468, 60],
                    [970, 90]
                ], 'div-gpt-ad-1731238739615-0').addService(googletag.pubads());
                googletag.pubads().enableSingleRequest();
                googletag.enableServices();
            });
        </script>

        <script>
            window.googletag = window.googletag || {
                cmd: []
            };
            googletag.cmd.push(function() {
                googletag.defineSlot('/21781351181/bd_desktop_3', [
                    [300, 50],
                    [300, 100], 'fluid', [728, 90]
                ], 'div-gpt-ad-1731238848673-0').addService(googletag.pubads());
                googletag.pubads().enableSingleRequest();
                googletag.enableServices();
            });
        </script>

        <script>
            window.googletag = window.googletag || {
                cmd: []
            };
            googletag.cmd.push(function() {
                googletag.defineSlot('/21781351181/bd_desktop_4', ['fluid', [300, 100],
                    [300, 250],
                    [728, 90]
                ], 'div-gpt-ad-1731239152173-0').addService(googletag.pubads());
                googletag.pubads().enableSingleRequest();
                googletag.enableServices();
            });
        </script>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
        window._taboola = window._taboola || [];
        _taboola.push({article:'auto'});
        !function (e, f, u, i) {
            if (!document.getElementById(i)){
            e.async = 1;
            e.src = u;
            e.id = i;
            f.parentNode.insertBefore(e, f);
            }
        }(document.createElement('script'),
        document.getElementsByTagName('script')[0],
        '//cdn.taboola.com/libtrc/businessdaynigeria/loader.js',
        'tb_loader_script');
        if(window.performance && typeof window.performance.mark == 'function')
            {window.performance.mark('tbl_ic');}
    </script>

    <amp-analytics type="taboola" id="taboola"> 
        <script type = "application/json"> 
        { 

            "vars":{ 

                "aid":"businessdaynigeria" 

            } 

        } 
        </script> 
    </amp-analytics>

    <script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//data.businessday.ng/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '2']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
</head>
<?php if ( $show_programmatic_ads ) : ?>
<div class="d-none">
    <script>
        window.googletag = window.googletag || {
            cmd: []
        };
        googletag.cmd.push(function() {
            googletag.defineSlot('/21781351181/bd_mobile_1', [
                [300, 50],
                [300, 100],
                [320, 100],
                [320, 50],
                [300, 250],
                [336, 280], 'fluid'
            ], 'div-gpt-ad-1731239615531-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>

    <script>
        window.googletag = window.googletag || {
            cmd: []
        };
        googletag.cmd.push(function() {
            googletag.defineSlot('/21781351181/bd_mobile_2', ['fluid', [300, 100],
                [300, 250],
                [300, 50],
                [320, 50],
                [320, 100],
                [336, 280]
            ], 'div-gpt-ad-1731239712211-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>

    <script>
        window.googletag = window.googletag || {
            cmd: []
        };
        googletag.cmd.push(function() {
            googletag.defineSlot('/21781351181/bd_mobile_3', [
                [300, 100],
                [336, 280],
                [300, 250],
                [300, 50],
                [320, 100]
            ], 'div-gpt-ad-1731239786872-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>

    <script>
        window.googletag = window.googletag || {
            cmd: []
        };
        googletag.cmd.push(function() {
            googletag.defineSlot('/21781351181/bd_mobile_4', [
                [336, 280],
                [300, 100],
                [300, 50],
                [320, 100],
                [300, 250]
            ], 'div-gpt-ad-1731239857708-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>
</div>
<?php endif; ?>

<script defer src="https://terrific.live/terrific-sdk.js" storeId="hcIgBSw8yP8qpUmQrosv"></script>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header>
        <?php
        $premiums = [];
        $premium_urls = [];
        $max_premium = 0;
        $slider_speed = 20000;

        // Custom direct premium programmatic protection for staging
        if (is_front_page()) {
            $premium_lead = get_option('premium_leaderboard');
            $count = isset($premium_lead['leaderboard_count']) && $premium_lead['leaderboard_count'] !== '' ? intval($premium_lead['leaderboard_count']) : 4;
            $slider_speed = isset($premium_lead['slider_speed']) && $premium_lead['slider_speed'] !== '' ? intval($premium_lead['slider_speed']) : 20000;

            if (is_array($premium_lead)) {
                for ($i = 1; $i <= $count; $i++) {
                    $img = $premium_lead['image' . $i] ?? '';
                    $url = $premium_lead['url' . $i] ?? '';
                    if (!empty($img)) {
                        $premiums[] = $img;
                        $premium_urls[] = $url;
                        $max_premium++;
                    }
                }
            }

            if ($max_premium > 0) {
                $rand_index = rand(0, $max_premium - 1);
                $selected_image = $premiums[$rand_index];
                $selected_url = $premium_urls[$rand_index];
                echo '<a id="premium_lederboard_url" href="' . esc_url($selected_url) . '" target="_blank"> <img id="premium_leaderboard" class="premium_leaderboard" src="' . esc_url($selected_image) . '" alt="premium_leaderboard_ads" max-width="100%" height="auto"/> </a>';
            }
        }
        ?>
        <?php if (is_front_page()) : ?>
        <script>
            var premiums = <?= json_encode($premiums) ?>;
            var premium_urls = <?= json_encode($premium_urls) ?>;
            var slider_speed = <?= $slider_speed ?>;
            console.log('image rand length ' + premiums.length);
            if (premiums.length === 0) {

            } else {
                var max_premium = <?= $max_premium ?>;
                console.log(premiums);
                // console.log('image rand is '+max_premium);
                var img = document.getElementById("premium_leaderboard");
                var timer = setInterval(function() {


                    var premium_rand = Math.floor(Math.random() * max_premium);
                    img.src = premiums[premium_rand];
                    // console.log('image rand src'+premiums[premium_rand] );
                    // console.log('image rand is '+premium_rand);
                    var href = document.getElementById('premium_lederboard_url');
                    // console.log('href_ '+ href);
                    // console.log('newhref_ '+ premium_urls[premium_rand]);

                    href.onclick = function(event) {
                        event.preventDefault();
                        window.location.href = premium_urls[premium_rand];
                    };

                }, slider_speed);
            }
        </script>
        <?php endif; ?>
        <section class="top-header">
            <div class="container">
                <ul>
                    <li><?php echo date('l, F d, Y'); ?></li>
                </ul>
            </div>
        </section>
        <section class="desktop-only">
            <a href="<?= get_site_url() ?>" aria-label="Homepage">
                <img class="logo-banner" alt="businessday logo" src="<?= get_template_directory_uri() ?>/assets/build/images/bd-logo.png">
            </a>
        </section>
        <nav class="navbar navbar-expand-lg navbar-light main-menu">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'main_menu',
                            'menu_class' => 'navbar-nav',
                            'menu_id' => '',
                            'container' => '',
                            'walker' => new macho_bootstrap_walker()
                        )
                    );
                    ?>
                </div>
                <div class="mobile-logo"><a href="<?= get_site_url() ?>" aria-label="Homepage"><img alt="businessday logo" src="<?= get_template_directory_uri() ?>/assets/build/images/businessday.png"></a></div>
                <div class="menu-action">
                    <ul>
                        <li>
                            <a href="https://businessday.ng/search-page/">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </a>
                        </li>
                        <?php
                        // AeroPaywall account menu (desktop, >=769px — see
                        // responsive.scss:61 for the theme's real nav
                        // breakpoint; the offcanvas panel below carries the
                        // mobile equivalent). Previously a custom "theme
                        // adapter" reusing this file's own .dropdown-menu
                        // CSS (built for a now-removed competing
                        // subscription platform) — that's gone now in favor
                        // of just calling the plugin's own render(), which
                        // is a full right-side flyout panel, not a
                        // dropdown. No bespoke markup left to keep in sync
                        // with the plugin's contract; a plugin update that
                        // improves this design just shows up here for free.
                        ?>
                        <li class="user-menu-item">
                            <?php aero_paywall_nav_button(); ?>
                        </li>
                        <li class="offcanvvas-toggler">
                            <a href="#offcanvasExample" aria-label="menu" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


        </nav>
    </header>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">X</button>
        </div>
        <div class="offcanvas-body">
            <p class="text-center"> <a href="<?= get_site_url() ?>"><img src="<?= get_template_directory_uri() ?>/assets/build/images/businessday.png"></a></p>
            <p class="site-title">BusinessDay</p>
            <div class="search">
                <form role="search" method="get" action="">
                    <input type="search" name="s" value="" placeholder="Search...">
                    <i class="fa fa-search"></i>
                </form>
            </div>
            <!-- AeroPaywall account menu (mobile/offcanvas — below the
                 769px nav breakpoint the desktop .menu-action item above is
                 hidden by responsive.scss, so this is the reachable
                 equivalent there). A second, independent call — its own
                 trigger icon and its own flyout panel instance, same as
                 the desktop one above; aero_paywall_nav_button() no-ops
                 harmlessly if the plugin isn't fully configured yet. -->
            <div class="aero-mobile-nav">
                <?php aero_paywall_nav_button(); ?>
            </div>
            <div class="menu">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'secondary_menu',
                        'menu_class' => '',
                        'menu_id' => '',
                        'container' => '',
                        'walker' => new macho_bootstrap_walker()
                    )
                );
                ?>
            </div>
        </div>
    </div>

    <div class="tradingview-widget-container">
        <div class="tradingview-widget-container__widget"></div>
        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
            {
                "symbols": [{
                        "description": "",
                        "proName": "NSENG:NGXGROUP"
                    },
                    {
                        "description": "",
                        "proName": "FX_IDC:NGNUSD"
                    },
                    {
                        "description": "",
                        "proName": "FX_IDC:NGNGBP"
                    },
                    {
                        "description": "",
                        "proName": "FX_IDC:NGNEUR"
                    },
                    {
                        "description": "",
                        "proName": "ECONOMICS:NGNOE"
                    },
                    {
                        "description": "",
                        "proName": "FX_IDC:NGNJPY"
                    }
                ],
                "showSymbolLogo": true,
                "isTransparent": false,
                "displayMode": "adaptive",
                "colorTheme": "light",
                "locale": "en"
            }
        </script>
    </div>
    <?php
		$liveScoreActive = get_option( 'live_match' );
		if($liveScoreActive == 'yes'){
		
		 $query = new WP_Query( [
        'post_type'      => 'live_match',
        'nopaging'       => true,
        'posts_per_page' => '5',
        ] ); 
    ?>
    <div class="container" style="padding: 10px 10px; background-color: #f3f3f3;">
        <div class="blinking"></div>
        <div class="d-flex">
            <div class="col-1">
                <span class="fw-bold"> LIVE SCORE:</span>
            </div>
            <div class="col-11 fw-bold">
                <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                    <?php 
                        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); 
                        ?>
                        <?php
                        echo get_post_meta($post->ID, 'home_team', true)  . '  '; 
                        echo '<span class="text-danger">' . get_post_meta($post->ID, 'home_team_score', true) . '</span>';
                        echo '<span class="text-secondary"> vs </span>'; 
                        echo '<span class="text-danger">' . get_post_meta($post->ID, 'away_team_score', true)  . '</span>  '; 
                        echo get_post_meta($post->ID, 'away_team', true);
                        ?>
                        &nbsp <span style="color: #dcdcdc;">|</span>  &nbsp
                        <?php
                        endwhile;
                        endif;
                        wp_reset_postdata(); }
                    ?>
                </marquee>
            </div>
        </div>
	    
        
    </div>
    <script>
        window.googletag = window.googletag || {
            cmd: []
        };
    </script>
    <?php if ( $show_programmatic_ads ) : ?>
    <script>
        googletag.cmd.push(function() {
            // Define out-of-page slots dynamically
            googletag.defineOutOfPageSlot('/21781351181/bd_left_rail', googletag.enums.OutOfPageFormat.LEFT_SIDE_RAIL).addService(googletag.pubads());
            googletag.defineOutOfPageSlot('/21781351181/bd_right_rail', googletag.enums.OutOfPageFormat.RIGHT_SIDE_RAIL).addService(googletag.pubads());

            // Enable features
            googletag.pubads().enableSingleRequest();
            googletag.pubads().collapseEmptyDivs();
            googletag.enableServices();
        });
    </script>
    <?php endif; ?>