<div class="auth-form-container">
    <h2>Shaken or stirred?</h2>
    <small>Log in to decide.</small>
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