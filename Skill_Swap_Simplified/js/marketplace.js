// User data management
let currentUser = null;

// Function to update user information in the UI
function updateUserInfo(user) {
    const userNameElements = document.querySelectorAll('.user-name');
    userNameElements.forEach(element => {
        element.textContent = user ? user.name : 'User';
    });
}

// Function to load user data
async function loadUserData() {
    // For our current implementation, we'll use the auth module
    if (auth.isLoggedIn()) {
        currentUser = auth.currentUser;
        updateUserInfo(currentUser);
        return currentUser;
    }
    return null;
}

// Marketplace functionality
document.addEventListener('DOMContentLoaded', async function() {
    // Check if user is logged in
    if (!auth.isLoggedIn()) {
        window.location.href = 'login.html';
        return;
    }
    
    // Load user data first
    await loadUserData();

    // Update user information in the sidebar
    if (currentUser) {
        const userNameElement = document.querySelector('.offcanvas-body .user-name');
        if (userNameElement) {
            userNameElement.textContent = currentUser.name || 'User Name';
        }
    }

    // Filter functionality
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const categoryFilter = document.getElementById('categoryFilter');
    const levelFilter = document.getElementById('levelFilter');
    const majorFilter = document.getElementById('majorFilter');
    const applyFiltersButton = document.getElementById('applyFilters');
    const clearFiltersButton = document.getElementById('clearFilters');
    const skillCards = document.querySelectorAll('#skillsGrid .col-md-4');

    // Function to apply all filters together
    function applyAllFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;
        const level = levelFilter.value;
        const major = majorFilter.value;

        skillCards.forEach(card => {
            // Default visibility
            let shouldShow = true;

            // Apply search term filter
            if (searchTerm) {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-text').textContent.toLowerCase();
                const instructor = card.querySelector('.d-flex.align-items-center span').textContent.toLowerCase();
                
                if (!title.includes(searchTerm) && !description.includes(searchTerm) && !instructor.includes(searchTerm)) {
                    shouldShow = false;
                }
            }

            // Apply category filter
            if (category !== 'all' && shouldShow) {
                const cardCategory = card.querySelector('.badge').textContent.toLowerCase();
                if (!cardCategory.includes(category)) {
                    shouldShow = false;
                }
            }

            // Apply level filter (if we add data attributes for level)
            if (level !== 'all' && shouldShow) {
                const cardLevel = card.getAttribute('data-level') || '';
                if (cardLevel !== level) {
                    shouldShow = false;
                }
            }

            // Apply major filter (if we add data attributes for instructor major)
            if (major !== 'all' && shouldShow) {
                const cardMajor = card.getAttribute('data-major') || '';
                if (cardMajor !== major) {
                    shouldShow = false;
                }
            }

            // Set visibility
            card.style.display = shouldShow ? 'block' : 'none';
        });
    }

    // Event for search button
    searchButton.addEventListener('click', applyAllFilters);

    // Event for enter key in search
    searchInput.addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            applyAllFilters();
        }
    });

    // Event for apply filters button
    applyFiltersButton.addEventListener('click', applyAllFilters);

    // Event for clear filters button
    clearFiltersButton.addEventListener('click', function() {
        // Reset all form elements
        searchInput.value = '';
        categoryFilter.value = 'all';
        levelFilter.value = 'all';
        majorFilter.value = 'all';
        
        // Show all cards
        skillCards.forEach(card => {
            card.style.display = 'block';
        });
    });

    // Handle logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            auth.logout();
            window.location.href = 'index.html';
        });
    }

    // Function to create a skill card
    function createSkillCard(skill, instructor) {
        // Use the template
        const template = document.getElementById('marketplace-card-template');
        const skillCard = template.content.cloneNode(true);
        
        // Fill in the data
        skillCard.querySelector('.card-title').textContent = skill.title;
        skillCard.querySelector('.card-text').textContent = skill.description;
        
        // Set category with proper background color
        const categoryBadge = skillCard.querySelector('.skill-category');
        categoryBadge.textContent = skill.category;
        
        // Set appropriate badge color based on category
        if (skill.category.toLowerCase().includes('technology')) {
            categoryBadge.classList.add('bg-primary');
        } else if (skill.category.toLowerCase().includes('design')) {
            categoryBadge.classList.add('bg-success');
        } else if (skill.category.toLowerCase().includes('language')) {
            categoryBadge.classList.add('bg-info');
        } else if (skill.category.toLowerCase().includes('music')) {
            categoryBadge.classList.add('bg-warning');
        } else {
            categoryBadge.classList.add('bg-secondary');
        }
        
        // Set level
        skillCard.querySelector('.skill-level').textContent = skill.level + ' Level';
        
        // Set instructor
        skillCard.querySelector('.instructor-name').textContent = instructor || 'Unknown Instructor';
        
        // Set data attributes for filtering
        const cardContainer = skillCard.querySelector('.col-md-4');
        cardContainer.dataset.level = skill.level.toLowerCase();
        if (currentUser && currentUser.major) {
            cardContainer.dataset.major = currentUser.major;
        }
        
        // Add connect button functionality
        const connectBtn = skillCard.querySelector('.btn-primary');
        connectBtn.addEventListener('click', () => {
            alert(`Request sent to connect with the instructor for ${skill.title}!`);
        });
        
        return skillCard;
    }

    // Handle add skill (modified to demonstrate template usage)
    const saveSkillBtn = document.getElementById('saveSkillBtn');
    if (saveSkillBtn) {
        saveSkillBtn.addEventListener('click', function() {
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

            // Save the skill
            const savedSkill = auth.addSkill(skill);
            
            // Optional: Add the new skill card to the grid for immediate display
            if (savedSkill && document.getElementById('skillsGrid')) {
                const skillCard = createSkillCard(savedSkill, currentUser.name);
                document.getElementById('skillsGrid').prepend(skillCard);
            }

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addSkillModal')).hide();
            document.getElementById('addSkillForm').reset();
            
            // Notify user
            alert('Skill added successfully! Check your dashboard to see your skills.');
        });
    }

    // Handle profile modal
    const profileModal = document.getElementById('profileModal');
    if (profileModal) {
        profileModal.addEventListener('show.bs.modal', function() {
            if (currentUser) {
                document.getElementById('profileName').value = currentUser.name || '';
                document.getElementById('profileEmail').value = currentUser.email || '';
                document.getElementById('profileBio').value = currentUser.bio || '';
                document.getElementById('profileInterests').value = (currentUser.interests || []).join(', ');
                
                if (document.getElementById('profileMajor')) {
                    document.getElementById('profileMajor').value = currentUser.major || '';
                }
                
                if (document.getElementById('profileYear')) {
                    document.getElementById('profileYear').value = currentUser.year || '';
                }
            }
        });

        const saveProfileBtn = document.getElementById('saveProfileBtn');
        if (saveProfileBtn) {
            saveProfileBtn.addEventListener('click', function() {
                const profileForm = document.getElementById('profileForm');
                
                if (profileForm.checkValidity()) {
                    const formData = {
                        name: document.getElementById('profileName').value,
                        email: document.getElementById('profileEmail').value,
                        bio: document.getElementById('profileBio').value,
                        interests: document.getElementById('profileInterests').value.split(',').map(item => item.trim())
                    };
                    
                    if (document.getElementById('profileMajor')) {
                        formData.major = document.getElementById('profileMajor').value;
                    }
                    
                    if (document.getElementById('profileYear')) {
                        formData.year = document.getElementById('profileYear').value;
                    }

                    // Update the profile
                    auth.updateProfile(formData);
                    currentUser = auth.currentUser;
                    
                    // Update UI
                    updateUserInfo(currentUser);
                    
                    // Update sidebar info
                    const userNameElement = document.querySelector('.offcanvas-body .user-name');
                    if (userNameElement) {
                        userNameElement.textContent = currentUser.name || 'User Name';
                    }
                    
                    bootstrap.Modal.getInstance(profileModal).hide();
                }
            });
        }
    }

    // Load skills for mentor search
    const findMentorModal = document.getElementById('findMentorModal');
    if (findMentorModal) {
        findMentorModal.addEventListener('show.bs.modal', function() {
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

        // Handle mentor search
        const searchMentorBtn = document.getElementById('searchMentorBtn');
        if (searchMentorBtn) {
            searchMentorBtn.addEventListener('click', function() {
                const skillId = document.getElementById('mentorSkill').value;
                const level = document.getElementById('mentorLevel').value;

                if (!skillId || !level) {
                    alert('Please select a skill and preferred mentor level');
                    return;
                }

                // In a real app, this would search for mentors
                // For demo purposes, redirect to marketplace
                bootstrap.Modal.getInstance(findMentorModal).hide();
                
                // Filter marketplace skills that match the selected level or higher
                const targetLevel = level;
                filterButtons.forEach(btn => btn.classList.remove('active'));
                filterButtons[0].classList.add('active'); // Set "All" as active
                
                skillCards.forEach(card => {
                    card.style.display = 'block';
                });
                
                alert('Searching for mentors... You can browse available skills in the marketplace.');
            });
        }
    }
});

// Helper function to get year label
function getYearLabel(year) {
    const yearLabels = {
        '1': 'Freshman',
        '2': 'Sophomore',
        '3': 'Junior',
        '4': 'Senior',
        '5': 'Graduate'
    };
    return yearLabels[year] || '';
} 