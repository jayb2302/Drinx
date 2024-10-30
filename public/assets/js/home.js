
function sortCocktails(sortOption) {
    fetch(`/?sort=${sortOption}`)
        .then(response => response.text())
        .then(data => {
            document.querySelector('.cocktails-container').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}