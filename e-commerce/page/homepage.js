window.addEventListener('scroll', function() {
    var background = document.getElementById('background');
    if (window.scrollY > 0) {
        background.classList.add('scrolled');
    } else {
        background.classList.remove('scrolled');
    }
    
});


// Logout functionality
document.getElementById('logoutBtn').addEventListener('click', function() {
    // Send a request to your backend logout endpoint
    fetch('/logout', {
        method: 'POST', // or 'GET' depending on your backend implementation
        credentials: 'same-origin' // Include cookies in the request if using sessions
    })
    .then(response => {
        if (response.ok) {
            // Redirect the user to the homepage or login page after successful logout
            window.location.href = '/'; // Redirect to homepage
        } else {
            // Handle errors or display a message to the user
            console.error('Logout failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
