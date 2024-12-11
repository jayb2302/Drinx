export function initializeRandomCocktail() {
    const cocktailContainer = document.querySelector('.randomRecipe');
    const randomButton = document.querySelector('.randomRecipeButton');

    if (!randomButton) return; // Ensure the button exists

    // Create audio objects for hover and click
    const hoverSound = new Audio('/assets/sounds/ShakeThat.mp3');
    const clickSound = new Audio('/assets/sounds/Shuffling.mp3');

    // Play hover sound on mouseover
    randomButton.addEventListener('mouseover', () => {
        console.log('Hover event triggered'); // Debugging log
        hoverSound.currentTime = 0; // Reset sound to the beginning
        hoverSound.play().catch(err => {
            console.error('Hover sound play failed:', err);
        });
    });

    // Play click sound and fetch random cocktail on button click
    randomButton.addEventListener('click', event => {
        event.preventDefault(); // Prevent default link behavior
        clickSound.currentTime = 0; // Reset sound to the beginning
        clickSound.play().catch(err => {
            console.error('Click sound play failed:', err);
        });

        fetchRandomCocktail(); // Fetch and display the random cocktail
    });
    if (!cocktailContainer || !randomButton) return; // Ensure elements exist

    function updateCocktailContent({ title, image, description, id }) {
        cocktailContainer.innerHTML = `
            <div class="recipeCard" style="background-image: url('/uploads/cocktails/${image}');">
                <div class"recipeInfo">
                    <h3>${title}</h3>
                    <small>${description}</small>
                    <a href="/cocktails/${id}-${encodeURIComponent(title)}" class="viewRecipe">View Recipe</a>
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

    // Attach click event listener to the "Shake it" button
    randomButton.addEventListener('click', event => {
        event.preventDefault(); // Prevent default link behavior
        fetchRandomCocktail(); // Fetch and display the random cocktail
    });
}