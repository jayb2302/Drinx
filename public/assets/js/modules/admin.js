/// Admin
export function initializeAdmin() {
    function toggleSection(sectionId) {
        // Hide all admin sections
        document.querySelectorAll('.admin-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show the selected section
        const selectedSection = document.getElementById(sectionId);
        if (selectedSection) {
            selectedSection.style.display = 'block';
        }
    }
      // Attach toggleSection function to buttons or links
      document.querySelectorAll('.admin-toggle-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const targetSection = button.getAttribute('data-target');
            toggleSection(targetSection);
        });
    });
}
