function toggleEditMode() {
    const form = document.getElementById('edit-profile-form');
    form.style.display = form.style.display === "none" ? "block" : "none";
}
function toggleDeleteSection() {
    const section = document.getElementById('deleteConfirmSection');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}