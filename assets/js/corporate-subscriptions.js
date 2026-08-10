document.addEventListener('DOMContentLoaded', () => {
    // Initialize Select2 if jQuery is available
    if (typeof jQuery !== 'undefined' && jQuery('#country').length) {
        jQuery('#country').select2({
            placeholder: "",
            width: '100%'
        });
    }
    const form = document.querySelector('.corporate-form');
    
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('form-message');
            
            if (submitBtn) {
                submitBtn.dataset.originalText = submitBtn.textContent;
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;
            }
            if (messageDiv) {
                messageDiv.style.display = 'none';
                messageDiv.textContent = '';
            }

            const formData = new FormData();
            formData.append('action', 'submit_corporate_subscription');
            formData.append('firstName', document.getElementById('firstName')?.value || '');
            formData.append('lastName', document.getElementById('lastName')?.value || '');
            formData.append('email', document.getElementById('email')?.value || '');
            formData.append('phone', document.getElementById('phone')?.value || '');
            formData.append('jobTitle', document.getElementById('jobTitle')?.value || '');
            formData.append('company', document.getElementById('company')?.value || '');
            formData.append('country', document.getElementById('country')?.value || '');
            
            const subType = document.querySelector('input[name="sub_type"]:checked');
            formData.append('sub_type', subType ? subType.value : '');
            
            const updates = document.getElementById('updates')?.checked ? 'Yes' : 'No';
            formData.append('updates', updates);

            fetch(corpSubAjax.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (messageDiv) {
                    messageDiv.style.display = 'block';
                    if (data.success) {
                        messageDiv.style.color = 'green';
                        messageDiv.textContent = data.data;
                        form.reset();
                    } else {
                        messageDiv.style.color = 'red';
                        messageDiv.textContent = data.data || 'An error occurred. Please try again.';
                    }
                }
            })
            .catch(error => {
                if (messageDiv) {
                    messageDiv.style.display = 'block';
                    messageDiv.style.color = 'red';
                    messageDiv.textContent = 'An error occurred. Please try again.';
                }
                console.error('Error:', error);
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.textContent = submitBtn.dataset.originalText;
                    submitBtn.disabled = false;
                }
            });
        });
    }

    // Dynamic text changes based on selected subscription type
    const radioButtons = document.querySelectorAll('input[name="sub_type"]');
    const heroLead = document.querySelector('.hero-lead');
    const heroBody = document.querySelector('.hero-body');
    const formTitles = document.querySelectorAll('.form-section-title');
    const infoTitle = formTitles.length > 1 ? formTitles[1] : null;

    const elementsToAnimate = [heroLead, heroBody, infoTitle].filter(el => el !== null);
    
    // Add CSS transition property for smooth fading
    elementsToAnimate.forEach(el => {
        el.style.transition = 'opacity 0.25s ease-in-out';
    });

    const contentData = {
        'wsj': {
            lead: 'Equip your company with trusted, timely, and comprehensive business news, information, and expert analysis it needs to stay informed and ahead.',
            body: 'As a business leader, you make daily decisions that shape the future of your organization. Businessday helps you make critical business decisions by connecting global, award-winning reporting, financial data, and expert insights together into a powerful, relevant, and customizable news platform that you can use to drive business growth at every level.',
            title: 'Provide your information:'
        },
        'bundle': {
            lead: 'Empower your company by maximizing business value. Give your team a competitive advantage with the Businessday full Bundle + Newsletters + Ecopy.',
            body: 'The bundle connects global news, company and market data, and expert financial insights across world-class publications — so your employees can make informed business decisions, faster. Together, these trusted publications deliver comprehensive coverage that supports your company\'s goals and your team\'s growth.',
            title: 'Provide your information to start saving 50%'
        }
    };

    radioButtons.forEach(radio => {
        radio.addEventListener('change', (e) => {
            const selectedValue = e.target.value;
            const data = contentData[selectedValue];
            
            if (data) {
                // Fade out
                elementsToAnimate.forEach(el => el.style.opacity = '0');
                
                // Wait for the fade out to finish (250ms), swap text, then fade in
                setTimeout(() => {
                    if (heroLead) heroLead.textContent = data.lead;
                    if (heroBody) heroBody.textContent = data.body;
                    if (infoTitle) infoTitle.textContent = data.title;
                    
                    elementsToAnimate.forEach(el => el.style.opacity = '1');
                }, 250);
            }
        });
    });
});
