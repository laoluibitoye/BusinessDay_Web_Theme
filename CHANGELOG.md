# Changelog

All notable changes to the **BusinessDay Theme (`bday_ng_v3.0`)** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [3.1.0] - 2026-08-05

### Added
- **Multiple Test Email Support**: Added ability to specify multiple comma-separated email addresses in WP Admin (**Settings > Remote Newsletter Pop-up**) to receive test alert emails simultaneously.
- **Daily Digest Test UI**: Added radio button controls in the WP Admin setting page to allow previewing and sending both **Instant Alert** and **Daily Digest** email templates.
- **Live Broadcast Support**: Added option in WP Admin panel to send live dispatches for both Instant and Daily Digest alert templates to opted-in subscribers with confirmation safeguards.
- **Standalone Test Script Enhancements**: Upgraded `send_test_alert.php` to accept `?type=digest` or `?type=instant` and `?email=user@domain.com` URL parameters.

### Changed
- **Alert Dispatch Tag Requirements**: Enforced that automated Instant Alerts and Daily Digests are only triggered for published articles tagged with `bdlead` or `bdrecent`.
- **Alert Type Labeling**: Updated remote API payload type labels to `live (instant)`, `live (digest)`, `test (instant)`, and `test (digest)` for cleaner tracking in the **FT Alert Reports** dashboard.
- **Reporting Metrics**: Updated FT Alert Reports calculation to display recipient count, Open Rate %, and Click-Through Rate (CTR) %.

### Fixed
- **Class Redeclaration Safeguard**: Wrapped `FluentCRM_Remote_Manager` in `if (!class_exists('FluentCRM_Remote_Manager'))` guards in both `functions.php` and `ft_alert_block.php` to prevent fatal errors if class is re-required.
- **Post Type Validation**: Added `WP_Post` object and post type checks in `handle_post_published` to ensure non-post status transitions do not throw type errors or fire stray API requests.

---

## [3.0.0] - 2026-08-04

### Added
- **Decoupled FluentCRM REST Integration**: Implemented `FluentCRM_Remote_Manager` class in `functions.php` to connect with remote FluentCRM server via WordPress Application Password REST Basic Authentication.
- **Category-to-List Mapping**: Added WP Admin interface to map WordPress post categories to remote FluentCRM newsletter lists.
- **Contextual Newsletter Box**: Added `append_contextual_newsletter_box()` filter on `the_content` to display single post category-matched newsletter subscription forms and recommended articles.
- **Automated Instant Alert Dispatcher**: Integrated `transition_post_status` hook to dispatch instant email payloads to remote REST bridge upon post publication.
- **Automated Daily Digest Cron**: Registered `fc_remote_daily_digest_cron` scheduled daily event to package 24-hour article summaries for remote CRM delivery.
