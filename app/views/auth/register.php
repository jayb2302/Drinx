<?php 

?>

<div class="container">
    <h2>Register</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message" style="color: red; margin-bottom: 10px;">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); // Clear the message after displaying ?>
        </div>
    <?php endif; ?>

    <form id="register-form" method="POST" action="<?php echo url('auth/register'); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>

    <div id="error-message" style="color: red; margin-top: 10px;"></div> <!-- For AJAX error messages -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#register-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Use the form's action attribute
                data: $(this).serialize(), // Serialize form data
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (!response.success) {
                        // Show error message if registration fails
                        $('#error-message').text(response.message);
                    } else {
                        // Redirect to login page or show a success message
                        window.location.href = '<?php echo url('/login'); ?>'; // Change to the appropriate URL for redirection
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle network or other errors
                    $('#error-message').text('An error occurred. Please try again.'); // Handle any other errors
                }
            });
        });
    });
    </script>
</div>
