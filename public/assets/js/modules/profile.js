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
   // Handle Image Preview and Validation for Profile Picture
   const fileInput = document.getElementById('profile_picture');
   const preview = document.getElementById('image-preview');
   const errorElement = document.createElement('span');
   const maxFileSize = 5 * 1024 * 1024; // 5MB
   const allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];

   errorElement.id = 'file-error';
   errorElement.style.color = 'red';
   errorElement.style.display = 'none';
   fileInput?.parentNode.appendChild(errorElement); // Add the error message element to the DOM

   fileInput?.addEventListener('change', function (event) {
       const file = event.target.files[0];
       errorElement.style.display = 'none'; // Hide error message initially
       errorElement.textContent = ''; // Clear any existing error message

       if (file) {
           // Validate file size
           if (file.size > maxFileSize) {
               errorElement.textContent = `The image size exceeds the allowed limit of ${maxFileSize / (1024 * 1024)} MB.`;
               errorElement.style.display = 'block';
               fileInput.value = ''; // Clear the input
               preview.style.display = 'none'; // Hide the preview
               return;
           }

           // Validate file format
           const fileExtension = file.name.split('.').pop().toLowerCase();
           if (!allowedExtensions.includes(fileExtension)) {
               errorElement.textContent = `Invalid file format. Allowed formats are: ${allowedExtensions.join(', ')}.`;
               errorElement.style.display = 'block';
               fileInput.value = ''; // Clear the input
               preview.style.display = 'none'; // Hide the preview
               return;
           }

           // If valid, display the preview
           const reader = new FileReader();
           reader.onload = function (e) {
               preview.src = e.target.result;
               preview.style.display = 'block'; // Make the preview visible
           };
           reader.readAsDataURL(file);
       } else {
           // Hide the preview if no file is selected
           preview.style.display = 'none';
       }
   });
}