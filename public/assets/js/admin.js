document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);
        const row = this.closest('tr'); // Get the row of the current form

        // Send an AJAX POST request
        fetch('/admin/update-status', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message); // Optional: Display a success message

                // Update the displayed account status in the row
                const statusSelect = this.querySelector('select[name="status_id"]');
                const selectedStatusText = statusSelect.options[statusSelect.selectedIndex].textContent;

                // Find the cell that displays the account status and update it
                const statusDisplayCell = row.querySelector('td:nth-child(3)'); // Assuming it's the 3rd cell
                if (statusDisplayCell) {
                    statusDisplayCell.textContent = selectedStatusText;
                }
            } else {
                alert(data.message); // Display error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
