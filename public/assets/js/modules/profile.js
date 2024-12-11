///// Profile
export function initializeProfile() {
    const editButton = document.getElementById('edit-profile-button');
    const form = document.getElementById('edit-profile-form');
    const overlay = document.getElementById('overlay');

    const closeForm = () => {
        if (form) form.style.display = 'none';
        if (overlay) overlay.style.display = 'none';
    };

    const openForm = () => {
        if (form) form.style.display = 'block';
        if (overlay) overlay.style.display = 'block';
    };
    


        // Toggle form and overlay visibility when edit button is clicked
        editButton?.addEventListener('click', (e) => {
            e.preventDefault();
            if (form && overlay) {
                const isVisible = form.style.display === 'block';
                if (isVisible) {
                    closeForm();
                } else {
                    openForm();
                }
            }
        });

            // Close form when clicking outside the form (on the overlay)
    overlay?.addEventListener('click', () => {
        closeForm();
    });


        // Optionally close form with a close button inside the form
        const closeButton = document.getElementById('close-form-button');
        closeButton?.addEventListener('click', () => {
            closeForm();
        });
    
        // Close form on escape key press
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeForm();
            }
        });


    document.getElementById('deleteAccountButton')?.addEventListener('click', () => {
        const section = document.getElementById('deleteConfirmSection');
        if (section) {
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    });

 // Initialize "Read more" functionality for bio
 const bioElements = document.querySelectorAll('.bio');

 bioElements.forEach((bio) => {
     const bioContent = bio.querySelector('.bio-content');
     const bioToggle = bio.querySelector('.bio-toggle');

     if (bioContent && bioToggle) {
         const fullText = bioContent.textContent.trim();
         const shortText = fullText.slice(0, 100); // Adjust the character limit as needed

         let isExpanded = false;

         if (fullText.length > 100) {
             bioContent.textContent = shortText + '...';
             bioToggle.style.display = 'inline';

             bioToggle.addEventListener('click', (e) => {
                 e.preventDefault();
                 isExpanded = !isExpanded;
                 bioContent.textContent = isExpanded ? fullText : shortText + '...';
                 bioToggle.innerHTML = isExpanded
                     ? '<a href="#" class="read-more">Show less</a>'
                     : '... <a href="#" class="read-more">Read more</a>';
             });
         } else {
             bioToggle.style.display = 'none';
         }
     }
 });
}