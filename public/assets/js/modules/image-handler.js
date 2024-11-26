// Generalized function for handling image preview and validation
export function initializeImageValidation(inputId, previewId, errorId) {
    const fileInput = document.getElementById(inputId);
    const preview = document.getElementById(previewId) || document.createElement('img');
    const errorElement = document.getElementById(errorId) || document.createElement('span');
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    const allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];

    // Configure the preview element if not provided
    if (!document.getElementById(previewId)) {
        preview.id = previewId;
        preview.style.display = 'none';
        preview.style.width = '100px';
        preview.style.marginTop = '10px';
        fileInput?.parentNode.insertBefore(preview, fileInput.nextSibling);
    }

    // Configure the error element if not provided
    if (!document.getElementById(errorId)) {
        errorElement.id = errorId;
        errorElement.style.color = 'red';
        errorElement.style.display = 'none';
        errorElement.style.marginTop = '5px'; // Ensure proper spacing
        fileInput?.parentNode.insertBefore(errorElement, preview.nextSibling);
    }

    fileInput?.addEventListener('change', function (event) {
        const file = event.target.files[0];
        errorElement.style.display = 'none'; // Hide error message initially
        errorElement.textContent = ''; // Clear any existing error message
        preview.style.display = 'none'; // Hide the preview initially

        if (file) {
            // Validate file size
            if (file.size > maxFileSize) {
                errorElement.textContent = `The image size exceeds the allowed limit of ${maxFileSize / (1024 * 1024)} MB.`;
                errorElement.style.display = 'block';
                fileInput.value = ''; // Clear the input
                return;
            }

            // Validate file format
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                errorElement.textContent = `Invalid file format. Allowed formats are: ${allowedExtensions.join(', ')}.`;
                errorElement.style.display = 'block';
                fileInput.value = ''; // Clear the input
                return;
            }

            // If valid, display the preview
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Make the preview visible
            };
            reader.readAsDataURL(file);
        }
    });
}
