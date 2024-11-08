document.addEventListener("DOMContentLoaded", function () {
    const cocktailContainer = document.querySelector(".about-container");
    if (!cocktailContainer) {
        console.warn("No element with class 'about-container' found.");
        return; // Exit if the container is not present
    }
    // Update the HTML content of the cocktail section with cocktail data
    function updateCocktailContent({ title, image, description, id }) {
        cocktailContainer.innerHTML = `
            <h1 class="about-heading">Welcome to Drinx,</h1>
            <h3 class="about-intro">The cocktail library that’s got social flair!</h3>
            <p class="about-description">Here, creativity meets community as people shake, stir, and share their best recipes. Drinx is your go-to spot to show off your creations, swap tips, and find your next favorite drink. Cheers to your next masterpiece — let’s make it one for the books!</p>
            <div class="recipe-container">
                <div class="recipe-card">
                    <h3>Random Cocktail: ${title}</h3>
                    <div>
                        <img src="/uploads/cocktails/${image}" alt="Random Cocktail Image" class="cocktailImage">
                        <p>${description}</p>
                        <a href="/cocktails/${id}-${encodeURIComponent(title)}" class="btn btn-primary">View Recipe</a>
                    </div>
                </div>
                <div class="randomButton">
                    <a href="#" class="random-recipe-button btn btn-secondary">Get Another Random Cocktail</a>
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
            if (event.target.classList.contains("random-recipe-button")) {
                event.preventDefault();
                fetchRandomCocktail();
            }
        });
    }

    // Initial setup
    attachButtonListener();
});