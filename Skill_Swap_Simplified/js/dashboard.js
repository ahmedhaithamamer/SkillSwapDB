// Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    if (!auth.isLoggedIn()) {
        window.location.href = 'login.html';
        return;
    }

    // Update user name
    const user = auth.currentUser;
    document.querySelectorAll('.user-name').forEach(element => {
        element.textContent = user.name;
    });

    // Load initial data
    loadUserProfile();
    loadUserSkills();
    updateStatistics();

    // Handle logout
    document.getElementById('logoutBtn').addEventListener('click', function() {
        auth.logout();
        window.location.href = 'index.html';
    });

    // Handle add skill
    document.getElementById('saveSkillBtn').addEventListener('click', function() {
        const title = document.getElementById('skillTitle').value;
        const description = document.getElementById('skillDescription').value;
        const category = document.getElementById('skillCategory').value;
        const level = document.getElementById('skillLevel').value;

        if (!title || !description || !category || !level) {
            alert('Please fill in all fields');
            return;
        }

        const skill = {
            title,
            description,
            category,
            level
        };

        auth.addSkill(skill);

        // Close modal and reset form
        bootstrap.Modal.getInstance(document.getElementById('addSkillModal')).hide();
        document.getElementById('addSkillForm').reset();
        loadUserSkills();
        updateStatistics();
    });

    // Handle profile update
    document.getElementById('saveProfileBtn').addEventListener('click', function() {
        const name = document.getElementById('profileName').value;
        const email = document.getElementById('profileEmail').value;
        const bio = document.getElementById('profileBio').value;
        const interests = document.getElementById('profileInterests').value.split(',').map(i => i.trim());
        const major = document.getElementById('profileMajor').value;
        const year = document.getElementById('profileYear').value;

        if (!name || !email || !major || !year) {
            alert('Please fill in required fields');
            return;
        }

        const profileData = {
            name,
            email,
            bio,
            interests,
            major,
            year
        };

        auth.updateProfile(profileData);

        // Update UI
        document.querySelectorAll('.user-name').forEach(element => {
            element.textContent = name;
        });
        
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        loadUserProfile();
    });

    // Handle skill filtering
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterSkillsByCategory(this.dataset.filter);
        });
    });

    // Load profile data when modal opens
    document.getElementById('profileModal').addEventListener('show.bs.modal', function() {
        const user = auth.currentUser;

        document.getElementById('profileName').value = user.name || '';
        document.getElementById('profileEmail').value = user.email || '';
        document.getElementById('profileBio').value = user.bio || '';
        document.getElementById('profileInterests').value = (user.interests || []).join(', ');
        
        // Set major and year if profile modal has these fields
        if (document.getElementById('profileMajor')) {
            document.getElementById('profileMajor').value = user.major || '';
        }
        
        if (document.getElementById('profileYear')) {
            document.getElementById('profileYear').value = user.year || '';
        }
    });

    // Load skills for mentor search
    document.getElementById('findMentorModal').addEventListener('show.bs.modal', function() {
        const mentorSkillSelect = document.getElementById('mentorSkill');
        const userSkills = auth.getSkills();

        // Clear existing options except the first one
        while (mentorSkillSelect.options.length > 1) {
            mentorSkillSelect.remove(1);
        }

        // Add user's skills as options
        userSkills.forEach(skill => {
            const option = document.createElement('option');
            option.value = skill.id;
            option.textContent = skill.title;
            mentorSkillSelect.appendChild(option);
        });
    });
});

// Load user's profile information
function loadUserProfile() {
    const user = auth.currentUser;
    
    // Update welcome section with user info
    const welcomeSection = document.querySelector('.py-5 .row .col-12.mb-4');
    if (welcomeSection) {
        welcomeSection.innerHTML = `
            <h2>Welcome, <span class="user-name">${user.name}</span>!</h2>
            <div class="d-flex align-items-center mt-2">
                <span class="badge bg-info me-2">${user.major || ''}</span>
                <span class="badge bg-secondary">${getYearLabel(user.year) || ''}</span>
            </div>
            <p class="text-muted mt-2">${user.bio || 'Manage your skills and connect with others.'}</p>
        `;
    }

    // Update sidebar user information
    const userNameElement = document.querySelector('.offcanvas-body .user-name');
    if (userNameElement) {
        userNameElement.textContent = user.name || 'User Name';
    }
}

// Helper function to get year label
function getYearLabel(year) {
    const yearLabels = {
        '1': 'Freshman (1st year)',
        '2': 'Sophomore (2nd year)',
        '3': 'Junior (3rd year)',
        '4': 'Senior (4th year)',
        '5': 'Graduate Student'
    };
    return yearLabels[year] || '';
}

// Load user's skills
function loadUserSkills() {
    const userSkills = auth.getSkills();
    const skillsContainer = document.getElementById('userSkills');

    if (!skillsContainer) return;
    
    // Clear the container
    skillsContainer.innerHTML = '';
    
    if (userSkills.length === 0) {
        // Show empty state message
        const emptyState = document.createElement('div');
        emptyState.className = 'col-12';
        emptyState.innerHTML = `
            <div class="alert alert-info">
                You haven't added any skills yet. Click the "Add New Skill" button to get started!
            </div>
        `;
        skillsContainer.appendChild(emptyState);
    } else {
        // Use the template to create skill cards
        const template = document.getElementById('skill-card-template');
        
        userSkills.forEach(skill => {
            // Clone the template
            const skillCard = template.content.cloneNode(true);
            
            // Fill in the data
            skillCard.querySelector('.card-title').textContent = skill.title;
            skillCard.querySelector('.card-text').textContent = skill.description;
            skillCard.querySelector('.skill-category').textContent = skill.category;
            skillCard.querySelector('.skill-level').textContent = skill.level;
            
            // Set up the delete button
            const deleteBtn = skillCard.querySelector('.delete-skill-btn');
            deleteBtn.addEventListener('click', () => deleteSkill(skill.id));
            
            // Add the card to the container
            skillsContainer.appendChild(skillCard);
        });
    }
}

// Delete skill
function deleteSkill(skillId) {
    if (!confirm('Are you sure you want to delete this skill?')) return;

    auth.removeSkill(skillId);
    loadUserSkills();
    updateStatistics();
}

// Filter skills by category
function filterSkillsByCategory(category) {
    const skillCards = document.querySelectorAll('#userSkills .col-md-6');
    
    skillCards.forEach(card => {
        if (category === 'all') {
            card.style.display = 'block';
        } else {
            const cardCategory = card.querySelector('.badge.bg-secondary').textContent.toLowerCase();
            card.style.display = cardCategory === category ? 'block' : 'none';
        }
    });
}

// Update statistics
function updateStatistics() {
    const totalSkills = auth.getSkills().length;
    const totalConnections = auth.currentUser?.connections || 0;

    document.getElementById('totalSkills').textContent = totalSkills;
    document.getElementById('totalConnections').textContent = typeof totalConnections === 'number' ? totalConnections : totalConnections.length || 0;
} 