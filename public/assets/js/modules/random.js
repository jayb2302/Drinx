export function initializeRandomCocktail() {
    const cocktailContainer = document.querySelector('.randomRecipe');
    const randomButton = document.querySelector('.randomRecipeButton');
    // Create audio objects for hover and click
    const hoverSound = new Audio('/assets/sounds/ShakeThat.mp3');
    hoverSound.preload = 'auto';
    hoverSound.load();
    const clickSound = new Audio('/assets/sounds/Shuffling.mp3');

    // Play hover sound on mouseover
    randomButton.addEventListener('mouseover', () => {
        hoverSound.currentTime = 0; 
        hoverSound.play().catch(err => console.error('Hover sound play failed:', err));
    });

    // Play click sound and fetch random cocktail on button click
    randomButton.addEventListener('click', event => {
        event.preventDefault(); 
        clickSound.currentTime = 0; 
        clickSound.play().catch(err => {
            console.error('Click sound play failed:', err);
        });

        shuffleCocktails();
    });
    
    function updateCocktailContent({ title, image, description, id }) {
        const firstSentence = description.split('. ')[0];
        cocktailContainer.innerHTML = `
            <div class="recipeCard" style="background-image: url('/uploads/cocktails/${image}');">
                <div class"recipeInfo">
                    <h3>${title}</h3>
                    <small>${firstSentence}</small>
                    <a href="/cocktails/${id}-${encodeURIComponent(title)}" class="viewRecipe">View Recipe</a>
                </div>
            </div>
        `;
    }

    async function fetchRandomCocktail() {
        try {
            const response = await fetch('/cocktails/random');
            return await response.json();
        } catch {
            return null;
        } // Return null if there's an error
    }

    function shuffleCocktails() {
        const shuffleDuration = 2400;
        const shuffleInterval = 100; 
        let elapsedTime = 0;

        const intervalId = setInterval(() => {
            fetchRandomCocktail().then(cocktail => {
                if (cocktail) {
                    updateCocktailContent(cocktail);
                }
            });

            elapsedTime += shuffleInterval;

            // Stop shuffling after 3 seconds
            if (elapsedTime >= shuffleDuration) {
                clearInterval(intervalId);

                // Fetch and display the final cocktail
                fetchRandomCocktail().then(finalCocktail => {
                    if (finalCocktail) {
                        updateCocktailContent(finalCocktail);
                    } else {
                        showError('No cocktail data available.');
                    }
                });
            }
        }, shuffleInterval);
    }


    function showError(message) {
        cocktailContainer.innerHTML = `<p>${message}</p>`;
    }

}