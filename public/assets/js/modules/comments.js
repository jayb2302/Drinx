export function initializeComments() {
    function attachDotsMenuListeners() {
        document.querySelectorAll('.dotsButton').forEach(button => {
            button.addEventListener('click', event => {
                event.stopPropagation();
                const menu = button.nextElementSibling;
                menu.classList.toggle('active');
            });
        });

        document.addEventListener('click', event => {
            if (!event.target.closest('.dotsButton') && !event.target.closest('.menu')) {
                document.querySelectorAll('.menu.active').forEach(menu => menu.classList.remove('active'));
            }
        });
    }

    function attachEditCommentListeners() {
        document.querySelectorAll('.editCommentButton').forEach(button => {
            button.addEventListener('click', function () {
                const commentId = this.dataset.commentId;
                document.getElementById(`editForm-${commentId}`).classList.toggle('hidden');
            });
        });
    }

    attachDotsMenuListeners();
    attachEditCommentListeners();

    document.addEventListener('Drinx.DOMUpdated', () => {
        attachDotsMenuListeners();
        attachEditCommentListeners();
    });
}
