<div class="auth-form-container">
    <h2>Shaken or stirred?</h2>
    <small>Log in to decide.</small>
    <?php
    // Display success message
    if (isset($_SESSION['success'])) {
        echo '<div id="message" class="success"><i class="fa-solid fa-bell success"></i><h4>' . htmlspecialchars($_SESSION['success']) . '</h4></div>';
        unset($_SESSION['success']); // Clear the success message after displaying
    }
    // Display error message 
    if (isset($_SESSION['error'])) {
        echo '<div id="message" class="alert alert-danger"><i class="fa-solid fa-bell error"></i><h4>' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // Clear the error message after displaying
    }
    ?>

    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>" />

        <div class="form-group">
        <label for="email" class="sr-only">Email</label>
            <input type="email" class="form-control" placeholder="Email Address" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="login-password" class="sr-only">Password:</label>
            <input type="password" placeholder="Password" class="form-control" id="login-password" name="password" required>
        </div>

        <button type="submit" class="secondary">Login</button>
    </form>
    <small> Drip, Drop, Drinx </small>
    <a href="/register" class="button-secondary">
        <span class="">
            Register now
        </span>
    </a>
    <h3>
        – the only thing missing is you - 
    </h3>
</div>