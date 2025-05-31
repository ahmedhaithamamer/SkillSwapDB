<?php
include '../Register/auth.php';
include '../Register/connect.php';
// Fetch all requests
$sql = "SELECT * FROM requests ORDER BY id DESC";  // Latest first
$result = $conn->query($sql);


$sql2 = "SELECT COUNT(*) AS total_requests FROM requests WHERE user_id = ?";
$stmt = $conn->prepare($sql2);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$total_requests = $row['total_requests'];


$sql3 = "SELECT COUNT(*) AS total_responses FROM responds WHERE user_id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $_SESSION['user_id']);
$stmt3->execute();
$result2 = $stmt3->get_result();

$row2 = $result2->fetch_assoc();
$total_responses = $row2['total_responses'];

include '../sidebar.php';
include '../nav.php';
include 'home_controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SkillSwap</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- Dashboard Content -->
    <div class="container py-5">
        <div class="row">
            <?php
                if (isset($_SESSION['status'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                        . $_SESSION['status'] .
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    unset($_SESSION['status']);
                }
            ?>
            <!-- Welcome Section -->
            <div class="col-12 mb-4">
                <h2>Welcome, <span class="user-name"><?php echo $_SESSION['auth_user']['username']; ?></span>!</h2>
                <!-- <p class="text-muted">Manage your skills and connect with others.</p> -->
            </div>

            <!-- Statistics Cards -->
            <div class="col-12 mb-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <form action="../Request/my_requests.php" method="POST">
                            <button class="card-button">
                                <div>
                                    <div class="card-title">My Requests</div>
                                    <div class="card-text" id="totalSkills"><?php echo $total_requests; ?></div>
                                </div>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="../Responses/my_responses.php" method="POST">
                            <button class="card-button bg-success text-white">
                                <div>
                                    <div class="card-title">My Responses</div>
                                    <div class="card-text" id="totalResponses"><?php echo $total_responses; ?></div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <!-- <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                                <i class="bi bi-plus-circle"></i> Add New Skill
                            </button>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#findMentorModal">
                                <i class="bi bi-people"></i> Find a Mentor
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Grid
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Your Skills</h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary active" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="tech">Technology</button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="design">Design</button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="language">Language</button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="music">Music</button>
                    </div>
                </div>
                <div class="row g-4" id="userSkills">
                    <!-- Skills will be loaded dynamically 
                </div>
            </div> -->
        </div>
    </div>

    <!-- Add Skill Modal -->
    <div class="modal fade" id="addSkillModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSkillForm">
                        <div class="mb-3">
                            <label for="skillTitle" class="form-label">Skill Title</label>
                            <input type="text" class="form-control" id="skillTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="skillDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="skillDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="skillCategory" class="form-label">Category</label>
                            <select class="form-select" id="skillCategory" required>
                                <option value="">Select a category</option>
                                <option value="tech">Technology</option>
                                <option value="design">Design</option>
                                <option value="language">Language</option>
                                <option value="music">Music</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="skillLevel" class="form-label">Level</label>
                            <select class="form-select" id="skillLevel" required>
                                <option value="">Select a level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSkillBtn">Save Skill</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="profileForm">
                        <div class="mb-3">
                            <label for="profileName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="profileName" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="profileEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileMajor" class="form-label">Major</label>
                            <input type="text" class="form-control" id="profileMajor" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileYear" class="form-label">Academic Year</label>
                            <select class="form-select" id="profileYear" required>
                                <option value="" disabled>Select your year</option>
                                <option value="1">Freshman (1st year)</option>
                                <option value="2">Sophomore (2nd year)</option>
                                <option value="3">Junior (3rd year)</option>
                                <option value="4">Senior (4th year)</option>
                                <option value="5">Graduate Student</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="profileBio" class="form-label">Bio</label>
                            <textarea class="form-control" id="profileBio" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="profileInterests" class="form-label">Interests</label>
                            <input type="text" class="form-control" id="profileInterests" placeholder="Separate interests with commas">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Learning Goal Modal -->
    <div class="modal fade" id="addGoalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Learning Goal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addGoalForm">
                        <div class="mb-3">
                            <label for="goalTitle" class="form-label">Goal Title</label>
                            <input type="text" class="form-control" id="goalTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="goalDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="goalDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="goalDeadline" class="form-label">Target Completion Date</label>
                            <input type="date" class="form-control" id="goalDeadline" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveGoalBtn">Save Goal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Find Mentor Modal -->
    <div class="modal fade" id="findMentorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Find a Mentor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="findMentorForm">
                        <div class="mb-3">
                            <label for="mentorSkill" class="form-label">Skill to Learn</label>
                            <select class="form-select" id="mentorSkill" required>
                                <option value="">Select a skill</option>
                                <!-- Skills will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mentorLevel" class="form-label">Preferred Mentor Level</label>
                            <select class="form-select" id="mentorLevel" required>
                                <option value="">Select a level</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="searchMentorBtn">Search Mentors</button>
                </div>
            </div>
        </div>
    </div>
    <?php
        include '../footer.php';
    ?>
</body>
</html> 