// Authentication state management
const auth = {
    // Initialize auth
    init() {
        // Check for logged in user
        const userData = localStorage.getItem('skillswap_user');
        if (userData) {
            this.currentUser = JSON.parse(userData);
            return true;
        }
        return false;
    },

    // Current user data
    currentUser: null,

    // Register a new user
    register(userData) {
        // In a real app, this would be an API call to the server
        // For demo purposes, we're storing in localStorage

        // Check if email already exists
        const existingUsers = this.getUsers();
        if (existingUsers.find(user => user.email === userData.email)) {
            return {
                success: false,
                message: 'Email already registered'
            };
        }

        // Generate a user ID
        userData.id = 'user_' + Date.now();
        
        // Add default skills and connections
        userData.skills = [];
        userData.connections = 0;
        userData.learningGoals = [];

        // Store the user
        existingUsers.push(userData);
        localStorage.setItem('skillswap_users', JSON.stringify(existingUsers));

        // Log the user in
        this.currentUser = userData;
        localStorage.setItem('skillswap_user', JSON.stringify(userData));

        return {
            success: true,
            userData
        };
    },

    // Login a user
    login(email, password) {
        // In a real app, this would be an API call to the server
        const users = this.getUsers();
        const user = users.find(u => u.email === email && u.password === password);
        
        if (user) {
            this.currentUser = user;
            localStorage.setItem('skillswap_user', JSON.stringify(user));
            return true;
        }
        
        return false;
    },

    // Logout the current user
    logout() {
        this.currentUser = null;
        localStorage.removeItem('skillswap_user');
    },

    // Check if a user is logged in
    isLoggedIn() {
        return this.currentUser !== null;
    },

    // Get all registered users
    getUsers() {
        const users = localStorage.getItem('skillswap_users');
        return users ? JSON.parse(users) : [];
    },

    // Update user profile
    updateProfile(profileData) {
        if (!this.currentUser) return false;
        
        // Update current user data
        this.currentUser = { ...this.currentUser, ...profileData };
        
        // Update in localStorage
        localStorage.setItem('skillswap_user', JSON.stringify(this.currentUser));
        
        // Update in users array
        const users = this.getUsers();
        const userIndex = users.findIndex(u => u.id === this.currentUser.id);
        if (userIndex !== -1) {
            users[userIndex] = this.currentUser;
            localStorage.setItem('skillswap_users', JSON.stringify(users));
        }
        
        return true;
    },

    // Add a skill to the current user
    addSkill(skill) {
        if (!this.currentUser) return false;
        
        // Add ID to skill
        skill.id = 'skill_' + Date.now();
        
        // Add to current user
        if (!this.currentUser.skills) {
            this.currentUser.skills = [];
        }
        this.currentUser.skills.push(skill);
        
        // Update storage
        this.updateProfile(this.currentUser);
        return skill;
    },

    // Get current user's skills
    getSkills() {
        return this.currentUser?.skills || [];
    }
};

// Initialize auth on page load
auth.init();

// Form validation
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required]');

    inputs.forEach(input => {
        if (!input.value) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Handle login form submission
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in and on explore.html page
    if (auth.isLoggedIn() && window.location.pathname.includes('explore.html')) {
        window.location.href = 'marketplace.html';
    }

    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            // Toggle eye icon
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }
    
    if (toggleConfirmPassword && confirmPasswordField) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            // Toggle eye icon
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateForm(loginForm)) {
                return;
            }

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Check if email ends with @nu.edu.eg
            if (!email.endsWith('@nu.edu.eg')) {
                const emailInput = document.getElementById('email');
                emailInput.classList.add('is-invalid');
                emailInput.nextElementSibling.textContent = 'Email must end with @nu.edu.eg';
                return;
            }

            if (auth.login(email, password)) {
                window.location.href = 'marketplace.html';
            } else {
                alert('Invalid email or password');
            }
        });
    }

    // Handle signup form submission
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateForm(signupForm)) {
                return;
            }

            const email = document.getElementById('email').value;
            
            // Check if email ends with @nu.edu.eg
            if (!email.endsWith('@nu.edu.eg')) {
                const emailInput = document.getElementById('email');
                emailInput.classList.add('is-invalid');
                emailInput.nextElementSibling.textContent = 'Email must end with @nu.edu.eg';
                return;
            }

            // Check if passwords match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                document.getElementById('confirmPassword').classList.add('is-invalid');
                return;
            }

            const userData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                major: document.getElementById('major').value,
                year: document.getElementById('year').value,
                bio: document.getElementById('bio').value || ''
            };

            const result = auth.register(userData);
            if (result.success) {
                window.location.href = 'dashboard.html';
            } else {
                alert(result.message);
            }
        });
    }

    // Update navbar based on auth state
    updateNavbar();
});

// Update navbar based on authentication state
function updateNavbar() {
    const isLoggedIn = auth.isLoggedIn();
    const navbarRight = document.querySelector('#navbarNav .d-flex');
    const userDropdown = document.querySelectorAll('.user-name');
    
    if (navbarRight) {
        if (isLoggedIn) {
            navbarRight.innerHTML = `
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="user-name">${auth.currentUser.name}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="dashboard.html"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="#" id="profileLink"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            `;
            
            // Add event listener to logout button
            document.getElementById('logoutBtn').addEventListener('click', function(e) {
                e.preventDefault();
                auth.logout();
                window.location.href = 'index.html';
            });
        } else {
            navbarRight.innerHTML = `
                <a href="login.html" class="btn btn-outline-primary me-2">Login</a>
                <a href="signup.html" class="btn btn-primary">Sign Up</a>
            `;
        }
    }
    
    // Update any user name elements
    if (userDropdown && isLoggedIn) {
        userDropdown.forEach(el => {
            el.textContent = auth.currentUser.name;
        });
    }
} 