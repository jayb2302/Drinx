<div class="form-container">
    <h2>Login</h2>

    <?php
    // Display success message
    if (isset($_SESSION['success'])) {
        echo '<div class="success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']); // Clear the success message after displaying
    }
    // Display error message 
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // Clear the error message after displaying
    }
    ?>

    <form action="/login" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="login-password">Password:</label>
            <input type="password" class="form-control" id="login-password" name="password" required>
        </div>

        <button type="submit" class="secondary">Login</button>
    </form>
    <button class="secondary">

        <a href="/register" class="secondary">Register</a>
    </button>
</div>