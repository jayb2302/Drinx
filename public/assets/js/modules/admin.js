/// Admin
export function initializeAdmin() {
    const sidebar = document.querySelector('.admin__sidebar');
    const toggleButton = document.getElementById('sidebarToggle');
    const sectionButtons = document.querySelectorAll('.admin-toggle-button');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
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
        if (window.matchMedia('(max-width: 768px)').matches) {
            sidebar.classList.remove('active');
        }
    }
    const defaultSectionId = 'userManagement';
    toggleSection(defaultSectionId);
      // Attach toggleSection function to buttons or links
      sectionButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const targetSection = button.getAttribute('data-target');
            toggleSection(targetSection);
        });
    });

    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', (e) => {
            const accordionItem = header.parentElement;
            const accordionBody = accordionItem.querySelector('.accordion-body');

            if (accordionBody.style.display === 'block') {
                accordionBody.style.display = 'none'; // Collapse
                accordionItem.classList.remove('active'); // Remove active class
            } else {
                // Close all other open accordion items
                document.querySelectorAll('.accordion-body').forEach(body => {
                    body.style.display = 'none';
                });
                document.querySelectorAll('.accordion-item').forEach(item => {
                    item.classList.remove('active');
                });

                accordionBody.style.display = 'block'; // Expand
                accordionItem.classList.add('active'); // Add active class
            }
        });
    });
}
