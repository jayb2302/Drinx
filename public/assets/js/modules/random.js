export function initializeRandomCocktail() {
    const cocktailContainer = document.querySelector('.aboutContainer');
    if (!cocktailContainer) return;

    function updateCocktailContent({ title, image, description, id }) {
        cocktailContainer.innerHTML = `
            <h1 class="aboutHeading">Welcome to Drinx,</h1>
            <h3 class="aboutIntro">The cocktail library that’s got social flair!</h3>
            <p class="aboutDescription">Cheers to your next masterpiece — let’s make it one for the books!</p>
            <div class="recipeContainer">
                <div class="recipeCard">
                    <h3>${title}</h3>
                    <div>
                        <img src="/uploads/cocktails/${image}" alt="Random Cocktail Image" class="cocktailImage">
                        <p>${description}</p>
                        <a href="/cocktails/${id}-${encodeURIComponent(title)}" class="viewRecipe">View Recipe</a>
                    </div>
                </div>
                <div class="randomButton">
                    <a href="#" class="randomRecipeButton">Shake It Up!</a>
                </div>
            </div>
        `;
    }

    function fetchRandomCocktail() {
        fetch('/cocktails/random')
            .then(response => response.json())
            .then(data => {
                if (data.title) updateCocktailContent(data);
                else showError('No cocktail data available.');
            })
            .catch(() => showError('Sorry, we couldn’t load a new cocktail right now. Please try again later.'));
    }

    function showError(message) {
        cocktailContainer.innerHTML = `<p>${message}</p>`;
    }

    cocktailContainer.addEventListener('click', event => {
        if (event.target.classList.contains('randomRecipeButton')) {
            event.preventDefault();
            fetchRandomCocktail();
        }
    });
}
