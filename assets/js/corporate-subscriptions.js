document.addEventListener('DOMContentLoaded', () => {
    // The form interaction relies entirely on native HTML/CSS inputs.
    // If further interactive logic is required (e.g., changing prices or form fields based on the selected radio button), it would go here.
    
    const form = document.querySelector('.corporate-form');
    
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Form submitted! We will get back to you shortly.');
        });
    }
});
