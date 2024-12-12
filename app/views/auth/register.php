<div class="auth-form-container">
    <h2>Register</h2>
    <?php
    // Display any error messages (optional)
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // Clear the error after displaying
    }
    ?>
    <form method="POST" action="/register">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
        <div class="form-group">
            <label  for="username" class="sr-only">Username:</label>
            <input type="text" placeholder="Username" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="email" class="sr-only">Email:</label>
            <input type="email" placeholder="Email Address" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="register-password" class="sr-only">Password:</label>
            <input type="password" placeholder="Password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <button class="secondary" style="flex-grow: 1;" type="submit">Register</button>
        </div>
    </form>
</div>