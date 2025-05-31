// Custom JavaScript for SkillSwap

document.addEventListener('DOMContentLoaded', function() {
    // Load featured skills
    loadFeaturedSkills();
});

// Load Featured Skills
function loadFeaturedSkills() {
    const skillsContainer = document.getElementById('featuredSkills');
    if (!skillsContainer) return;
    
    // Sample skills data
    const featuredSkills = [
        {
            title: 'Web Development',
            description: 'Learn HTML, CSS, and JavaScript to build modern websites.',
            icon: 'bi-code-slash'
        },
        {
            title: 'Graphic Design',
            description: 'Master design principles and tools like Adobe Photoshop.',
            icon: 'bi-palette'
        },
        {
            title: 'Language Exchange',
            description: 'Practice speaking and writing in different languages.',
            icon: 'bi-translate'
        },
        {
            title: 'Music Lessons',
            description: 'Learn to play instruments or improve your singing skills.',
            icon: 'bi-music-note-beamed'
        }
    ];
    
    // Clear existing content
    skillsContainer.innerHTML = '';
    
    // Add skills to the container
    featuredSkills.forEach(skill => {
        const skillCard = document.createElement('div');
        skillCard.className = 'col-md-6 col-lg-3 mb-4';
        skillCard.innerHTML = `
            <div class="card h-100 animate-fade-in">
                <div class="card-body">
                    <i class="bi ${skill.icon} fs-1 mb-3 text-primary"></i>
                    <h5 class="card-title">${skill.title}</h5>
                    <p class="card-text">${skill.description}</p>
                </div>
            </div>
        `;
        skillsContainer.appendChild(skillCard);
    });
} 