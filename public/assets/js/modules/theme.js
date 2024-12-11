export function initializeThemeSwitcher() {
    const toggleButton = document.getElementById('theme-toggle');
    const rootElement = document.documentElement;
    const sunIcon = document.getElementById('icon-sun');
    const moonIcon = document.getElementById('icon-moon');

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        rootElement.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'dark') {
            sunIcon.style.display = 'none';
            moonIcon.style.display = 'inline';
        }
    }

    toggleButton?.addEventListener('click', () => {
        const currentTheme = rootElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        rootElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        if (newTheme === 'dark') {
            sunIcon.style.display = 'none';
            moonIcon.style.display = 'inline';
        } else {
            sunIcon.style.display = 'inline';
            moonIcon.style.display = 'none';
        }
    });
    // Deselect input fields on Esc key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            const activeElement = document.activeElement;
            if (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA') {
                activeElement.blur(); // Remove focus from the input or textarea
            }
        }
    });
}