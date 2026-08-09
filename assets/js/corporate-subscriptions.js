document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.corporate-form');
    
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Form submitted! We will get back to you shortly.');
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
            lead: 'Empower your company by maximizing business value. Give your team a competitive advantage with the Businessday + Barron\'s + MarketWatch Digital Bundle.',
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
