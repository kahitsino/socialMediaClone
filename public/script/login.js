// Login form handling
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            document.getElementById('userError').textContent = '';
            document.getElementById('passwordError').textContent = '';
            document.getElementById('login').classList.remove('is-invalid');
            document.getElementById('password').classList.remove('is-invalid');
            
            // Get form values
            const login = document.getElementById('login').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Basic validation
            let hasError = false;
            
            if (!login) {
                document.getElementById('userError').textContent = 'Pakienter ng email o username';
                document.getElementById('login').classList.add('is-invalid');
                hasError = true;
            }
            
            if (!password) {
                document.getElementById('passwordError').textContent = 'Pakienter ng password';
                document.getElementById('password').classList.add('is-invalid');
                hasError = true;
            }
            
            if (hasError) return;
            
            // Send login request
            fetch('php/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `login=${encodeURIComponent(login)}&password=${encodeURIComponent(password)}`,
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'home.html';
                } else {
                    if (data.error === 'user') {
                        document.getElementById('userError').textContent = 'Hindi mahanap ang user';
                        document.getElementById('login').classList.add('is-invalid');
                    } else if (data.error === 'password') {
                        document.getElementById('passwordError').textContent = 'Mali ang password';
                        document.getElementById('password').classList.add('is-invalid');
                    } else {
                        alert('May error sa pag-login. Subukan ulit.');
                    }
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                alert('May error sa connection. Subukan ulit.');
            });
        });
    }
});