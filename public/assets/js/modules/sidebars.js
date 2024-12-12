export function initializeSidebars() {
    const leftSidebar = document.querySelector('.container__left');
    const rightSidebar = document.querySelector('.container__right');
    const leftToggleButton = document.querySelector('#toggle-left');
    const rightToggleButton = document.querySelector('#toggle-right');
    const categoryLinks = document.querySelectorAll('.category-sidebar a'); 

    if (!leftSidebar || !rightSidebar || !leftToggleButton || !rightToggleButton) {
        // console.warn('Sidebar elements or toggle buttons are missing.');
        return;
    }
 // Helper to update tooltip
 function updateTooltip(button, isExpanded) {
    button.setAttribute('data-tooltip', isExpanded ? 'Show panel' : 'Hide panel');
}
    function setInitialState() {
        const isMobile = window.matchMedia('(max-width: 768px)').matches;

        if (isMobile) {
            leftSidebar.classList.add('collapsed');
            rightSidebar.classList.add('collapsed');
            leftToggleButton.setAttribute('aria-expanded', 'false');
            rightToggleButton.setAttribute('aria-expanded', 'false');
        } else {
            leftSidebar.classList.remove('collapsed');
            rightSidebar.classList.remove('collapsed');
            leftToggleButton.setAttribute('aria-expanded', 'true');
            rightToggleButton.setAttribute('aria-expanded', 'true');
        }
    }

    // Toggle the left sidebar
    function toggleLeftSidebar() {
        leftSidebar.classList.toggle('collapsed');
        const isExpanded = leftToggleButton.getAttribute('aria-expanded') === 'true';
        leftToggleButton.setAttribute('aria-expanded', !isExpanded);
    }

    // Toggle the right sidebar
    function toggleRightSidebar() {
        rightSidebar.classList.toggle('collapsed');
        const isExpanded = rightToggleButton.getAttribute('aria-expanded') === 'true';
        rightToggleButton.setAttribute('aria-expanded', !isExpanded);
    }

    // Collapse left sidebar when a category link is clicked
    function collapseLeftSidebarOnCategoryClick(event) {
        if (window.matchMedia('(max-width: 768px)').matches) {
            leftSidebar.classList.add('collapsed');
            leftToggleButton.setAttribute('aria-expanded', 'false');
        }
    }

    // Add event listeners for the toggle buttons
    leftToggleButton.addEventListener('click', toggleLeftSidebar);
    rightToggleButton.addEventListener('click', toggleRightSidebar);

    // Add event listeners to category links
    categoryLinks.forEach((link) => {
        link.addEventListener('click', collapseLeftSidebarOnCategoryClick);
    });

    // Set the initial state based on the viewport width
    setInitialState();

    // Update the state when the viewport is resized
    window.addEventListener('resize', setInitialState);
}