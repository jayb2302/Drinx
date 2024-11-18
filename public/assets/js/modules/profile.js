export function initializeProfile() {
    document.getElementById('edit-profile-form')?.addEventListener('click', () => {
        const form = document.getElementById('edit-profile-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

    document.getElementById('deleteConfirmSection')?.addEventListener('click', () => {
        const section = document.getElementById('deleteConfirmSection');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    });
}
