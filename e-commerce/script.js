window.addEventListener('scroll', function() {
    var background = document.getElementById('background');
    if (window.scrollY > 0) {
        background.classList.add('scrolled');
    } else {
        background.classList.remove('scrolled');
    }
});


document.getElementById('logoutBtn').addEventListener('click', function() {

    fetch('/logout', {
        method: 'POST', 
        credentials: 'same-origin' 
    })
    .then(response => {
        if (response.ok) {
            
            window.location.href = '/'; 
        } else {
          
            console.error('Logout failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
