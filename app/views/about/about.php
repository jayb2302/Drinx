<section class="aboutSection">
    <?php if (!isset($_SESSION['user'])): ?>
        <button class="button-secondary">
            <a href="/login">Login</a>
        </button>
    <?php endif; ?>
    <div class="aboutContainer">
        <h1 class="aboutHeading">Welcome to Drinx,</h1>
        <!-- <h3>The cocktail library that thinks it's a social media platform!</h3> -->
        <h3 class="aboutIntro">The cocktail library that’s got social flair!</h3>
        <p class="aboutDescription">Shake, stir, and share your best recipes. Drinx is your go-to spot to show off your creations, swap tips, and find your next favorite drink. Cheers to your next masterpiece <br>— let’s make it one for the books! </p>
        <div class="buttonContainer">
            <a href="#" class="randomRecipeButton">Shake it</a>
        </div>
    </div>
</section>