// Session management
function checkSession() {
    fetch('php/check_session.php', {
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        const currentPage = window.location.pathname.split('/').pop();
        
        // Kung nasa protected page pero hindi logged in, redirect sa login page
        if (!data.loggedin && protectedPages.includes(currentPage)) {
            window.location.href = 'index.html';
            return;
        }
        
        if (data.loggedin) {
            // Update welcome message kung may ganito sa page
            const welcomeMsg = document.getElementById('welcomeMessage');
            if (welcomeMsg) welcomeMsg.textContent = `Welcome, ${data.username}`;
            
            // Show protected content
            const protectedContent = document.querySelector('.protected-content');
            if (protectedContent) protectedContent.style.display = 'block';
            
            // Additional user data kung kailangan
            if (data.user_id) {
                // Store user ID sa page para magamit sa iba pang functions
                window.currentUserId = data.user_id;
            }
        }
    })
    .catch(() => {
        const currentPage = window.location.pathname.split('/').pop();
        if (protectedPages.includes(currentPage)) {
            window.location.href = 'index.html';
        }
    });
}

// Logout function
function logout() {
    fetch('php/logout.php', {
        credentials: 'include',
        method: 'POST'
    })
    .then(() => window.location.href = 'index.html');
}

// Array of protected pages
const protectedPages = ['home.html', 'gallery.html', 'messages.html', 'profile.html']; 
// Dagdagan mo dito ang lahat ng HTML files na kailangan ng login

// Initialize session check on protected pages
const currentPage = window.location.pathname.split('/').pop();
if (protectedPages.includes(currentPage)) {
    document.addEventListener('DOMContentLoaded', checkSession);
    setInterval(checkSession, 300000); // Check every 5 minutes
}