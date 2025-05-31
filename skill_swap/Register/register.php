<?php
include 'connect.php';

// $sql = "SELECT name FROM skills ORDER BY name ASC";
// $result = $conn->query($sql);

// $skills = [];
// if ($result->num_rows > 0) {
//     while($row = $result->fetch_assoc()) {
//         $skills[] = $row['name'];
//     }
// }

// echo json_encode($skills);

//Sisi's edit 20-5-2025
/*
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name      = $_POST['name'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $cpassword = $_POST['confirmPassword'];
    $major     = $_POST['major'];
    $year      = $_POST['year'];
    $bio       = $_POST['bio'] ?? '';

    // Skills processing
    $skills_json = $_POST['skills'];
    $skills_array = json_decode($skills_json, true);

    $skill_names = [];
    foreach ($skills_array as $skill) {
        $skill_names[] = $skill['value'];
    }

    $skills_str = implode(", ", $skill_names);

    if ($password != $cpassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, major, year, bio, skills) 
                VALUES ('$name', '$email', '$hashed_password', '$major', '$year', '$bio', '$skills_str')";

        if ($conn->query($sql) === TRUE) {
            session_start();
            $_SESSION['user_id'] = $conn->insert_id;
            echo "<script>alert('Account created successfully!');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - SkillSwap</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.html">SkillSwap</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="http://localhost:8888/skill_swap/HomePage/home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="http://localhost:8888/skill_swap/register/login.php" class="btn btn-outline-primary me-2">Login</a>
                <a href="http://localhost:8888/skill_swap/register/register.php" class="btn btn-primary">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

<!-- Sign Up Form -->
<section class="py-5">
    <div class="container">
        <?php
            if (isset($_SESSION['status'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                    . $_SESSION['status'] .
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                unset($_SESSION['status']);
            }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Create Account</h2>
                        <form id="signupForm" action="register_controller.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" name="email" placeholder="username@nu.edu.eg" required>
                                <small class="form-text text-muted">Must be a valid Nile University email ending with @nu.edu.eg</small>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Phone</label>
                                <input type="phone" class="form-control" name="phone" placeholder="01**********" required>
                            </div>
                            <div class="mb-3">
                                    <label for="major" class="form-label">Major</label>
                                    <select class="form-select" id="major" name="major" required>
                                        <option value="" selected disabled>Select your major</option>
                                        <optgroup label="School of Sciences">
                                            <option value="Applied Biotechnology">Applied Biotechnology</option>
                                            <option value="Industrial Chemistry">Industrial Chemistry</option>
                                        </optgroup>
                                        <optgroup label="School of Business">
                                            <option value="Management and Entrepreneurship">Management and Entrepreneurship</option>
                                            <option value="Finance">Finance</option>
                                            <option value="Operation Management and Supply Chain">Operation Management and Supply Chain</option>
                                        </optgroup>
                                        <optgroup label="School of Information Technology and Computer Science">
                                            <option value="Integrated Marketing Communications">Integrated Marketing Communications</option>
                                            <option value="Computer Science">Computer Science</option>
                                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                                            <option value="Biomedical Informatics">Biomedical Informatics</option>
                                            <option value="Bioinformatics">Bioinformatics</option>
                                        </optgroup>
                                        <optgroup label="School of Engineering">
                                            <option value="Electronics and Computer Engineering">Electronics and Computer Engineering</option>
                                            <option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
                                            <option value="Civil and Construction Engineering">Civil and Construction Engineering</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                            <option value="Industrial Engineering">Industrial Engineering</option>
                                            <option value="Architecture and Urban Design">Architecture and Urban Design</option>
                                        </optgroup>
                                        <optgroup label="School of Digital Humanities">
                                            <option value="Digital Media and Communication">Digital Media and Communication</option>
                                            <option value="Big Data in Culture and Society">Big Data in Culture and Society</option>
                                            <option value="Psychology">Psychology</option>
                                        </optgroup>
                                        <optgroup label="School of Water Science and Food Security">
                                            <option value="Food Security">Food Security</option>
                                            <option value="Water Science">Water Science</option>
                                        </optgroup>
                                        <optgroup label="School of Energy and Environmental Engineering">
                                            <option value="Data and Environmental Engineering">Data and Environmental Engineering</option>
                                            <option value="Smart Energy Systems Engineering">Smart Energy Systems Engineering</option>
                                        </optgroup>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select your major.
                                    </div>
                                </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Academic Year</label>
                                <select class="form-select" name="year" required>
                                    <option value="" selected disabled>Select your year</option>
                                    <option value="1">Freshman</option>
                                    <option value="2">Sophomore</option>
                                    <option value="3">Junior</option>
                                    <option value="4">Senior</option>
                                    <option value="5">Graduate Student</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="skills" class="form-label">Your Skills</label>
                                <input name="skills" id="skills-input" class="form-control" placeholder="Type to search skills..." required>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio (Optional)</label>
                                <textarea class="form-control" name="bio" rows="3" placeholder="Tell us a bit about yourself..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" required>
                                    <button class="btn btn-outline-primary" type="button" onclick="togglePassword('password')"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required>
                                    <button class="btn btn-outline-primary" type="button" onclick="togglePassword('confirmPassword')"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                                </label>
                            </div>
                            <div class="d-grid">
                                <button name="register_btn" type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.html">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include('../footer.php');
?>

<!-- Tagify JS -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Script -->
<script>
   const input = document.querySelector('#skills-input');
    let tagify;

    fetch('get_skills.php')
        .then(res => res.json())
        .then(data => {
            tagify = new Tagify(input, {
                whitelist: data,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-look",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        });
        document.getElementById('signupForm').addEventListener('submit', function () {
    input.value = JSON.stringify(tagify.value);
});


</script>

</body>
</html>


<?php
    /*
    <!DOCTYPE html>
    <html>
    <head>
        <title>Skill Swap - Create Account</title>
        <link rel="stylesheet" href="style.css">

        <!-- Tagify CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

        <!-- Tagify JS -->
        <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    </head>

    <body>
    <div class="form-container">
        <h2>Create Account</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="cpassword" placeholder="Confirm Password" required><br>

            <label>Select Your Skills:</label><br>
            <input name="skills" id="skills-input" placeholder="Type to search skills..." required><br><br>

            <button type="submit">Register</button>
        </form>
    </div>

    <!-- Tagify Activation Script (put outside form) -->
    <script>
        var input = document.querySelector('#skills-input');

        // The available skills (you can add more here)
        var availableSkills = [
            "C++ Programming",
            "Python Development",
            "Flutter Development",
            "Web Development",
            "Data Analysis",
            "Machine Learning",
            "Database Management",
            "Cybersecurity",
            "Mobile App Development",
            "UI/UX Design"
        ];

        // Activate Tagify
        new Tagify(input, {
            whitelist: availableSkills,
            maxTags: 10,
            dropdown: {
                maxItems: 20,
                classname: "tags-look",
                enabled: 0,
                closeOnSelect: false
            }
        });
    </script>
    </body>
    </html>
    */ 
?>