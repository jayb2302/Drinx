export function initializeSocialMedia() {
    // console.log('Initializing social media links...');
    const platformSelect = document.getElementById('platform-select');
    const socialLinkContainer = document.getElementById('social-link-container');

    if (!platformSelect || !socialLinkContainer) {
        console.warn('Social media elements not found on the page.');
        return;
    }

    function updateInputVisibility() {
        // Hide all input fields
        socialLinkContainer.querySelectorAll('.social-link-group').forEach(inputGroup => {
            inputGroup.style.display = 'none'; // Hide the input group
        });

        // Show the selected platform's input field
        const selectedPlatformId = platformSelect.value;
        if (selectedPlatformId) {
            const selectedInput = document.getElementById(`social-input-${selectedPlatformId}`);
            if (selectedInput) {
                selectedInput.style.display = 'block'; // Show the input group
            }
        }
    }

    // Add event listener for dropdown changes
    platformSelect.addEventListener('change', updateInputVisibility);

    // Trigger updateInputVisibility on page load to set the initial state
    document.addEventListener('DOMContentLoaded', updateInputVisibility);
}
