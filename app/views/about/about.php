<section class="aboutSection">
    <?php if (!isset($_SESSION['user'])): ?>
        
            <a href="/login" class="button-secondary">
                <span>Login</span>
            </a>
        <div class="aboutContainer">
            <h1 class="aboutHeading">Welcome to Drinx,</h1>
            <h3 class="aboutIntro">The cocktail library that’s got social flair!</h3>
            <p class="aboutDescription">Shake, stir, and share your best recipes. Drinx is your go-to spot to show off your creations, swap tips, and find your next favorite drink. Cheers to your next masterpiece <br>— let’s make it one for the books!</p>
        </div>
    <?php endif; ?>
    <div class="aboutContainer">
        <div class="buttonContainer">
            <a href="#" class="randomRecipeButton"><i class="fa-solid fa-handshake-angle fa-shake"></i> Let us Shake it</a>
        </div>
        <div class="randomRecipe">
            <!-- Random cocktail will appear here -->
        </div>
        <div class="randomRecipe">
            <!-- Random cocktail will appear here -->
        </div>
    </div>
</section>