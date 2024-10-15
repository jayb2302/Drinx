<!-- app/views/layout/header.php -->
<?php
require_once __DIR__ . '/head.php';
require_once __DIR__ . '/../../controllers/AuthController.php'; // Include AuthController

// Instantiate AuthController
$authController = new AuthController();
$isLoggedIn = $authController->isLoggedIn(); // Check if the user is logged in
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo url('/'); ?>">Drinx</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if ($isLoggedIn): ?>
                <li>
                    <p>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?>!</p>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="logout">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="loginLink">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="registerLink">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Container for loading forms -->
<div id="form-container"></div>

<!-- Include jQuery (Make sure jQuery is included before this script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        let currentForm = null; // To keep track of the currently displayed form

        // Toggle login form visibility
        $('#loginLink').click(function (e) {
            e.preventDefault();
            var $formContainer = $('#form-container');

            // Check if the login form is currently displayed
            if (currentForm === 'login') {
                $formContainer.hide(); // Hide the container if it's already open
                currentForm = null; // Reset currentForm
            } else {
                $.ajax({
                    url: 'login', // Direct path to the login form
                    method: 'GET',
                    success: function (response) {
                        $formContainer.html(response).show(); // Load the form and show the container
                        currentForm = 'login'; // Set current form to login

                        // Add AJAX form submission handling
                        $('#ajaxLoginForm').submit(function (e) {
                            e.preventDefault(); // Prevent the default form submission
                            $.ajax({
                                url: 'auth/login', // Ensure the URL is correct
                                method: 'POST', // Use POST for the login request
                                data: $(this).serialize(), // Serialize form data
                                success: function (response) {
                                    // Assuming your server returns a JSON response
                                    if (response.success) {
                                        window.location.href = '<?php echo url('/'); ?>'; // Redirect to homepage on success
                                    } else {
                                        $('#loginMessage').html(response.message); // Show error message
                                    }
                                },
                                error: function () {
                                    $('#loginMessage').html('<p>Error logging in. Please try again.</p>');
                                }
                            });
                        });
                    },
                    error: function () {
                        $formContainer.html('<p>Error loading login form.</p>').show();
                    }
                });
            }
        });
        $('#ajaxLoginForm').submit(function (e) {
            e.preventDefault(); // Prevent the default form submission
            $.ajax({
                url: '/login', // Change this to match your route configuration
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        window.location.href = '<?php echo url('/'); ?>'; // Redirect on success
                    } else {
                        $('#loginMessage').html(response.message); // Show error message
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown); // Log the error
                    console.log("Response: ", jqXHR.responseText); // Log the response text
                    $('#loginMessage').html('<p>Error logging in. Please try again.</p>');
                }
            });

        });

        // Toggle register form visibility
        $('#registerLink').click(function (e) {
            e.preventDefault();
            var $formContainer = $('#form-container');

            // Check if the register form is currently displayed
            if (currentForm === 'register') {
                $formContainer.hide(); // Hide the container if it's already open
                currentForm = null; // Reset currentForm
            } else {
                $.ajax({
                    url: 'register', // Adjust to the correct URL to load the registration form
                    method: 'GET',
                    success: function (response) {
                        $formContainer.html(response).show(); // Load the form and show the container
                        currentForm = 'register'; // Set current form to register
                    },
                    error: function () {
                        $formContainer.html('<p>Error loading registration form.</p>').show();
                    }
                });
            }
        });

        // Handle logout via AJAX
        $('#logout').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: 'logout', // Adjust to the correct URL for logout
                method: 'POST', // You can use POST for logout
                success: function (response) {
                    window.location.href = '<?php echo url('/'); ?>'; // Redirect after logout
                },
                error: function () {
                    alert('Error logging out.');
                }
            });
        });
    });
</script>