export function initializeMessageTimeout(timeout = 5000) {
    const messageElement = document.getElementById('message');
    if (messageElement) {
        setTimeout(() => {
            messageElement.classList.add('fade-out');
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 1000); 
        }, timeout);
    }
}
