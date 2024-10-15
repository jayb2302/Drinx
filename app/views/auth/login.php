<?php 

?>

<div class="container">
    <h2>Login</h2>
    
    <?php
    // Display any error messages (optional)
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . sanitize($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // Clear the error after displaying
    }
    ?>

    <form id="ajaxLoginForm" method="POST"> <!-- Added ID here -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    
    <div id="loginMessage"></div> <!-- For displaying error/success messages -->
</div>
