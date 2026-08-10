<?php
/* Template Name: Corporate Subscriptions */

get_header(); ?>

<div class="corporate-subscriptions-wrap">
    <!-- Hero Section -->
    <header class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title"><span class="wsj-logo">Businessday</span> CORPORATE SUBSCRIPTIONS</h1>
                <p class="hero-lead">Equip your company with trusted, timely, and comprehensive business news, information, and expert analysis it needs to stay informed and ahead.</p>
                <p class="hero-body">As a business leader, you make daily decisions that shape the future of your organization. Businessday helps you make critical business decisions by connecting global, award-winning reporting, financial data, and expert insights together into a powerful, relevant, and customizable news platform that you can use to drive business growth at every level.</p>
            </div>
            
            <div class="hero-form-card" id="pricing-form-section">
                <h3 class="form-section-title">Select subscription type:</h3>
                
                <div class="subscription-options">
                    <label class="radio-label">
                        <input type="radio" name="sub_type" value="wsj" checked>
                        <span class="custom-radio"></span>
                        <span class="radio-text">Businessday only</span>
                    </label>
                    <div class="bundle-option">
                        <label class="radio-label">
                            <input type="radio" name="sub_type" value="bundle">
                            <span class="custom-radio"></span>
                            <span class="radio-text">Businessday full Bundle + Newsletters + Ecopy</span>
                        </label>
                        <span class="badge">SAVE 50% OR MORE</span>
                    </div>
                </div>

                <hr class="form-divider">

                <h3 class="form-section-title">Provide your information:</h3>
                <p class="form-subtitle">Complete your details and we will get back to you shortly.</p>

                <form class="corporate-form" onsubmit="event.preventDefault();">
                    <div class="form-row">
                        <div class="input-group">
                            <input type="text" id="firstName" required>
                            <label for="firstName">First Name*</label>
                        </div>
                        <div class="input-group">
                            <input type="text" id="lastName" required>
                            <label for="lastName">Last Name*</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-group">
                            <input type="email" id="email" required>
                            <label for="email">Business Email*</label>
                        </div>
                        <div class="input-group">
                            <input type="tel" id="phone">
                            <label for="phone">Business Phone (Optional)</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-group">
                            <input type="text" id="jobTitle" required>
                            <label for="jobTitle">Job Title*</label>
                        </div>
                        <div class="input-group">
                            <input type="text" id="company" required>
                            <label for="company">Company*</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-group select-group">
                            <select id="country" required>
                                <option value="" disabled selected></option>
                                <?php
                                $countries = array(
                                    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Holy See", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                                );
                                foreach($countries as $country) {
                                    echo '<option value="' . esc_attr($country) . '">' . esc_html($country) . '</option>';
                                }
                                ?>
                            </select>
                            <label for="country">Country*</label>
                            <div class="select-arrow">&#9662;</div>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="updates">
                        <label for="updates">I would like to receive updates and information about Corporate Subscriptions, recommended content and special offers from Dow Jones and affiliates. I can unsubscribe at any time.</label>
                    </div>

                    <p class="terms-text">By clicking the button below, you agree to the Dow Jones <a href="#">Privacy Notice</a> and <a href="#">Cookie Notice</a>.</p>

                    <button type="submit" class="btn btn-primary btn-block">Get Pricing</button>
                    <div id="form-message" style="margin-top: 15px; font-weight: bold; text-align: center; display: none;"></div>
                </form>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="features-heading">Empower Critical Business Decisions</h2>
            
            <div class="features-grid">
                <div class="feature-item">
                    <h4 class="feature-title">CLIENT-FACING ROLES</h4>
                    <p class="feature-text">Give deep, become the subject matter expert, and land the next deal with relevant and timely business news.</p>
                </div>
                <div class="feature-item">
                    <h4 class="feature-title">BUSINESS ANALYSTS & RESEARCHERS</h4>
                    <p class="feature-text">Build, validate, and deliver actionable recommendations with trusted and accessible financial information and analysis.</p>
                </div>
                <div class="feature-item">
                    <h4 class="feature-title">PROCUREMENT & VENDOR SPECIALISTS</h4>
                    <p class="feature-text">Increase value with subscription bundles that offer immediacy, exclusivity, and reliability.</p>
                </div>
                <div class="feature-item">
                    <h4 class="feature-title">AUTHORITATIVE, EXPERT INSIGHTS</h4>
                    <p class="feature-text">Breaking and in-depth coverage across 200+ topics, industry segments, data and alerts provide an authoritative voice in global business and financial news.</p>
                </div>
                <div class="feature-item">
                    <h4 class="feature-title">ROBUST BUSINESS & FINANCIAL DATA</h4>
                    <p class="feature-text">Comprehensive company profiles, stock market and economic data, and private equity to create actionable business insights.</p>
                </div>
                <div class="feature-item">
                    <h4 class="feature-title">FLEXIBLE AND EASY TO MANAGE</h4>
                    <p class="feature-text">Multiple corporate signup options with simple onboarding and licenses and save admin time.</p>
                </div>
            </div>

            <div class="features-cta">
                <a href="#pricing-form-section" class="btn btn-primary">Get Pricing</a>
                <p class="cta-italic">Become one of the thousands of companies that empower their workforce with a Corporate Subscription</p>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Section -->
    <section class="bottom-cta-section">
        <div class="container text-center">
            <h2 class="bottom-cta-heading">Get the trusted resource your team needs</h2>
            <a href="#pricing-form-section" class="btn btn-outline">Get Pricing</a>
        </div>
    </section>
</div>

<?php get_footer(); ?>
