export function initializeSidebars() {
    const leftSidebar = document.querySelector('.container__left');
    const rightSidebar = document.querySelector('.container__right');
    const leftToggleButton = document.querySelector('#toggle-left');
    const rightToggleButton = document.querySelector('#toggle-right');

    if (!leftSidebar || !rightSidebar || !leftToggleButton || !rightToggleButton) {
        console.warn('Sidebar elements or toggle buttons are missing.');
        return;
    }

    // Toggle the left sidebar
    function toggleLeftSidebar() {
        leftSidebar.classList.toggle('collapsed');
        leftToggleButton.classList.toggle('collapsed');
        const isExpanded = leftToggleButton.getAttribute('aria-expanded') === 'true';
        leftToggleButton.setAttribute('aria-expanded', !isExpanded);
    }

    // Toggle the right sidebar
    function toggleRightSidebar() {
        rightSidebar.classList.toggle('collapsed');
        rightToggleButton.classList.toggle('collapsed');
        const isExpanded = rightToggleButton.getAttribute('aria-expanded') === 'true';
        rightToggleButton.setAttribute('aria-expanded', !isExpanded);
    }

    // Add event listeners for the toggle buttons
    leftToggleButton.addEventListener('click', toggleLeftSidebar);
    rightToggleButton.addEventListener('click', toggleRightSidebar);
}