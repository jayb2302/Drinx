///// Profile
export function initializeProfile() {
    document.getElementById('edit-profile-button')?.addEventListener('click', () => {
        const form = document.getElementById('edit-profile-form');
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    });

    document.getElementById('deleteAccountButton')?.addEventListener('click', () => {
        const section = document.getElementById('deleteConfirmSection');
        if (section) {
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    });
}