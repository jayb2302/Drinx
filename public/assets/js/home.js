
function sortCocktails(sortOption) {
    fetch(`/?sort=${sortOption}`)
        .then(response => response.text())
        .then(data => {
            document.querySelector('.cocktails-container').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}

function toggleUserManagement() {
    const userManagement = document.getElementById('userManagement');
    userManagement.style.display = userManagement.style.display === 'none' ? 'block' : 'none';
}