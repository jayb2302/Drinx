async function checkSession() {
    try {
        const response = await fetch('/session-check', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Identify it as an AJAX request
            },
        });

        if (response.ok) {
            const data = await response.json();
            if (data.session_expired) {
                console.warn('Session expired, reloading page.');
                window.location.reload();
            }
        } else {
            console.error('Non-OK response:', response);
        }
    } catch (error) {
        console.error('Error checking session status:', error);
    }
}

// Poll the server every hour to check if the session is still active
setInterval(checkSession, 3600000);
