<?php
/**
 * Template Name: FluentCRM Newsletter Landing Page
 * Description: A premium, conversion-optimized standalone landing page to onboard subscribers to various newsletter feeds.
 */

// Let's load the header of your theme
get_header();

$manager = FluentCRM_Remote_Manager::get_instance();
$all_lists = $manager->get_cached_lists();
$visible_list_ids = $manager->get_setting('visible_lists', []);
$saved_snippets = $manager->get_setting('list_snippets', []);

$visible_lists = [];
if (!empty($all_lists) && !empty($visible_list_ids)) {
    foreach ($all_lists as $list) {
        if (in_array($list['id'], $visible_list_ids)) {
            $visible_lists[] = $list;
        }
    }
}

// Make sure the main assets and script engines are enqueued
FluentCRM_Remote_Widget_Helper::enqueue_assets();
?>

<!-- Premium Onboarding Landing Page Layout Styles -->
<style>
.fc-landing-page-hero {
    background: linear-gradient(135deg, #FFF1E0 0%, #ffffff 100%);
    padding: 60px 0 40px 0;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}
.fc-landing-hero-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}
.fc-landing-hero-container h1 {
    font-size: 2.8rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 16px;
    line-height: 1.2;
}
.fc-landing-hero-container p {
    font-size: 1.15rem;
    color: #475569;
    line-height: 1.6;
    margin: 0;
}

.fc-landing-body-section {
    padding: 50px 0 80px 0;
    background-color: #fafafa;
}
.fc-landing-container-wide {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}
.fc-landing-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
}

@media (min-width: 992px) {
    .fc-landing-grid {
        grid-template-columns: 7fr 5fr; /* 60% newsletters list, 40% signup form card */
    }
}

.fc-landing-list-wrapper h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-top: 0;
    margin-bottom: 24px;
    border-bottom: 2px solid #0f172a;
    padding-bottom: 10px;
}

/* Elegant Newsletter Grid Card Design */
.fc-newsletter-card-item {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 18px;
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.fc-newsletter-card-item:hover {
    border-color: #ba141a;
    box-shadow: 0 8px 16px rgba(186, 20, 26, 0.05);
    transform: translateY(-2px);
}
.fc-newsletter-card-item.fc-card-selected {
    border-color: #ba141a;
    background-color: #fffafb;
    box-shadow: 0 8px 20px rgba(186, 20, 26, 0.08);
}

.fc-card-checkbox-container {
    padding-top: 3px;
}
.fc-card-checkbox-container input[type="checkbox"] {
    accent-color: #ba141a;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.fc-card-text-container {
    flex: 1;
}
.fc-card-text-container h3 {
    font-size: 1.2rem;
    font-weight: 750;
    color: #0f172a;
    margin: 0 0 8px 0;
    line-height: 1.3;
}
.fc-card-text-container p {
    font-size: 0.92rem;
    color: #475569;
    line-height: 1.5;
    margin: 0;
}

/* Signup Card Side Panel */
.fc-landing-form-sticky-panel {
    position: sticky;
    top: 30px;
}
.fc-landing-card-body {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 36px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.03), 0 1px 3px rgba(0,0,0,0.01);
}
.fc-landing-card-body h2 {
    font-size: 1.4rem;
    font-weight: 800;
    color: #0f172a;
    margin-top: 0;
    margin-bottom: 8px;
}
.fc-landing-card-body p.fc-lead-text {
    font-size: 0.92rem;
    color: #64748b;
    margin-top: 0;
    margin-bottom: 24px;
    line-height: 1.4;
}

/* Styling alignment fix inside columns */
.fc-landing-card-body .fc-field-row {
    margin-bottom: 15px;
}
.fc-landing-card-body .fc-field-group {
    margin-bottom: 15px;
}
</style>

<!-- Main Marketing Hero Banner -->
<section class="fc-landing-page-hero">
    <div class="fc-landing-hero-container">
        <h1>Subscribe to Our Newsletters</h1>
        <p>Curated business news, analytical columns, and industry-focused reports delivered directly to your inbox. Select your topics below to customise your onboarding.</p>
    </div>
</section>

<!-- Content Grid Section -->
<section class="fc-landing-body-section">
    <div class="fc-landing-container-wide">
        <?php if (empty($visible_lists)): ?>
            <div style="background:#ffffff; border: 1.5px solid #fca5a5; padding: 24px; border-radius: 12px; text-align:center; max-width:600px; margin: 0 auto;">
                <p style="color:#991b1b; font-weight:700; margin:0 0 10px 0;">No active newsletter lists are currently exposed.</p>
                <p style="font-size:13px; color:#475569; margin:0;">Please navigate to your WordPress Administration screen (<strong>Settings -> Remote Newsletter Pop-up</strong>) to synchronize lists and configure visible feeds.</p>
            </div>
        <?php else: ?>
            <div class="fc-landing-grid">
                
                <!-- LEFT SIDEBAR: Interactive Newsletter Selection Cards -->
                <div class="fc-landing-list-wrapper">
                    <h2>1. Choose Your Feeds</h2>
                    <div class="fc-newsletter-cards-list">
                        <?php foreach ($visible_lists as $list): 
                            $list_id = intval($list['id']);
                            $snippet = isset($saved_snippets[$list_id]) ? $saved_snippets[$list_id] : 'Receive the latest news and strategic insights direct from this publication feed.';
                        ?>
                            <div class="fc-newsletter-card-item" data-list-id="<?php echo esc_attr($list_id); ?>">
                                <div class="fc-card-checkbox-container">
                                    <input type="checkbox" 
                                           id="landing-cb-<?php echo esc_attr($list_id); ?>" 
                                           value="<?php echo esc_attr($list_id); ?>" 
                                           class="fc-newsletter-source-cb">
                                </div>
                                <div class="fc-card-text-container">
                                    <h3><?php echo esc_html($list['title']); ?></h3>
                                    <p><?php echo esc_html($snippet); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR: Sticky Subscription Panel -->
                <div class="fc-landing-form-sticky-panel">
                    <div class="fc-landing-card-body">
                        <h2>2. Subscriptions Form</h2>
                        <p class="fc-lead-text">Fill in your contact information below. We will synchronise your preferences instantly.</p>
                        
                        <!-- Form binds to the same high-performance AJAX fetch engine -->
                        <form id="fc-landing-signup-form" class="fc-ajax-signup-form" data-mode="landing">
                            <?php wp_nonce_field('fluent_popup_nonce_action', 'fluent_popup_nonce'); ?>
                            
                            <!-- Hidden list checkboxes mapped from the selection cards -->
                            <div id="fc-hidden-lists-receiver">
                                <?php foreach ($visible_lists as $list): ?>
                                    <input type="hidden" 
                                           name="crm_list_ids[]" 
                                           id="fc-hidden-cb-<?php echo esc_attr($list['id']); ?>" 
                                           value="<?php echo esc_attr($list['id']); ?>" disabled>
                                <?php endforeach; ?>
                            </div>

                            <div class="fc-field-row">
                                <div class="fc-field-column">
                                    <input type="text" name="first_name" placeholder="First Name" required>
                                </div>
                                <div class="fc-field-column">
                                    <input type="text" name="last_name" placeholder="Last Name" required>
                                </div>
                            </div>

                            <div class="fc-field-group">
                                <input type="email" name="email" placeholder="Email Address" required>
                            </div>

                            <div class="fc-field-group" style="margin-bottom: 20px; margin-top: 15px;">
                                <p style="font-size: 0.9rem; font-weight: 600; color: #0f172a; margin-bottom: 8px; margin-top:0;">Delivery Preference:</p>
                                <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: #475569; cursor: pointer;">
                                    <input type="radio" name="delivery_preference" value="instant" required style="margin-right: 6px;"> Instant Alerts (As news breaks)
                                </label>
                                <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: #475569; cursor: pointer;">
                                    <input type="radio" name="delivery_preference" value="digest" required style="margin-right: 6px;"> Daily Digest (Once a day)
                                </label>
                                <label style="display: block; font-size: 0.85rem; color: #475569; cursor: pointer; margin-bottom: 8px;">
                                    <input type="radio" name="delivery_preference" value="both" required style="margin-right: 6px;"> Both
                                </label>
                                <label style="display: block; font-size: 0.85rem; color: #475569; cursor: pointer;">
                                    <input type="radio" name="delivery_preference" value="none" required style="margin-right: 6px;"> Standard Newsletter Only (No Automated Alerts)
                                </label>
                            </div>

                            <button type="submit" class="fc-submit-btn">Complete Onboarding</button>
                        </form>
                        <div class="fc-response-message"></div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Click-to-Select Micro-Interactions JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.fc-newsletter-card-item');
    
    cards.forEach(card => {
        const checkbox = card.querySelector('.fc-newsletter-source-cb');
        const listId = card.getAttribute('data-list-id');
        const hiddenInput = document.getElementById('fc-hidden-cb-' + listId);

        // Click handler anywhere on the card
        card.addEventListener('click', function(e) {
            // If the user clicked the checkbox directly, don't double toggle
            if (e.target === checkbox) return;
            
            checkbox.checked = !checkbox.checked;
            handleToggle(card, checkbox, hiddenInput);
        });

        // Click handler directly on the checkbox
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                handleToggle(card, checkbox, hiddenInput);
            });
        }
    });

    function handleToggle(card, checkbox, hiddenInput) {
        if (checkbox.checked) {
            card.classList.add('fc-card-selected');
            if (hiddenInput) hiddenInput.disabled = false;
        } else {
            card.classList.remove('fc-card-selected');
            if (hiddenInput) hiddenInput.disabled = true; // Disabled fields are not submitted in forms
        }
    }
});
</script>

<?php
// Load the footer of the theme
get_footer();
