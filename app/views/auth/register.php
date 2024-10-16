<div class="form-container">
    <h2>Register</h2>
    <?php
    // Display any error messages (optional)
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // Clear the error after displaying
    }
    ?>
    <form method="POST" action="/user/store">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
</div>