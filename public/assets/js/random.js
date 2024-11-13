document.addEventListener("DOMContentLoaded", function () {
    const cocktailContainer = document.querySelector(".aboutContainer");
    if (!cocktailContainer) {
        return; // Exit if the container is not present
    }
    // Update the HTML content of the cocktail section with cocktail data
    function updateCocktailContent({ title, image, description, id }) {
        cocktailContainer.innerHTML = `
            <h1 class="aboutHeading">Welcome to Drinx,</h1>
            <h3 class="aboutIntro">The cocktail library that’s got social flair!</h3>
            <p class="aboutDescription"> Cheers to your next masterpiece — let’s make it one for the books!</p>
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

    // Fetch a random cocktail and update content or show error
    function fetchRandomCocktail() {
        fetch("/cocktails/random")
            .then(response => response.json())
            .then(data => data?.title ? updateCocktailContent(data) : showError("No cocktail data available."))
            .catch(error => {
                console.error("Error fetching random cocktail:", error);
                showError("Sorry, we couldn't load a new cocktail right now. Please try again later.");
            });
    }

    // Show error message in the container
    function showError(message) {
        cocktailContainer.innerHTML = `<p>${message}</p>`;
    }

    // Attach event listener to fetch a new cocktail when button is clicked
    function attachButtonListener() {
        cocktailContainer.addEventListener("click", function (event) {
            if (event.target.classList.contains("randomRecipeButton")) {
                event.preventDefault();
                fetchRandomCocktail();
            }
        });
    }

    // Initial setup
    attachButtonListener();
});