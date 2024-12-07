///// Profile
export function initializeProfile() {
    const editButton = document.getElementById('edit-profile-button');
    const form = document.getElementById('edit-profile-form');
    const closeButton = document.getElementById('close-form-button');
    

    // Toggle form visibility when edit button is clicked
    editButton?.addEventListener('click', (e) => {
        e.preventDefault();
        if (form) {
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }
    });

    // Close form when close button inside form is clicked
    closeButton?.addEventListener('click', () => {
        if (form) {
            form.style.display = 'none';
        }
    });

    document.getElementById('deleteAccountButton')?.addEventListener('click', () => {
        const section = document.getElementById('deleteConfirmSection');
        if (section) {
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    });
}