<?php
include '../Register/connect.php';
include '../Register/auth.php';
// Set memory limit if needed (but better to optimize first)
ini_set('memory_limit', '128M');

// Get all responses for the current user
$sql = "SELECT r.*
        FROM responds r 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$responses = [];
$responses_ids = [];
$requests_ids = [];

// Process responses and collect IDs in one loop
while ($row = $result->fetch_assoc()) {
    $responses[$row['id']] = $row;  // Store by ID for easy lookup
    $responses_ids[] = $row['id'];
    $requests_ids[] = $row['request_id'];
}

// Initialize empty arrays (avoids undefined variable warnings)
$grouped_skills = [];

if (!empty($requests_ids)) {
    // 1. Get all explicitly linked skills
    $placeholders = implode(',', array_fill(0, count($requests_ids), '?'));
    
    $sql_linked = "
        SELECT 
            ros.request_id,
            ros.skill_id AS offer_skill_id,
            s.name AS offer_skill_name,
            r.offer_money
        FROM 
            requests_offer_skills ros
        INNER JOIN 
            skills s ON ros.skill_id = s.id
        INNER JOIN
            requests r ON ros.request_id = r.id
        WHERE 
            ros.request_id IN ($placeholders)
    ";
    
    $stmt_linked = $conn->prepare($sql_linked);
    $stmt_linked->bind_param(str_repeat('i', count($requests_ids)), ...$requests_ids);
    $stmt_linked->execute();
    $result_linked = $stmt_linked->get_result();

    // Track which requests have linked skills
    $requests_with_linked_skills = [];
    while ($row = $result_linked->fetch_assoc()) {
        $grouped_skills[$row['request_id']]['skills'][] = [
            'offer_skill_id' => $row['offer_skill_id'],
            'offer_skill_name' => $row['offer_skill_name']
        ];
        $requests_with_linked_skills[$row['request_id']] = true;
        $grouped_skills[$row['request_id']]['is_linked'] = true;
        $grouped_skills[$row['request_id']]['offer_money'] = $row['offer_money'];
    }

    // 2. Find requests with offer_money but no linked skills
    // 2. Find requests with offer_money but no linked skills (single query solution)
    $sql_money = "SELECT r.id, r.offer_money
        FROM requests r
        LEFT JOIN requests_offer_skills ros ON r.id = ros.request_id
        WHERE r.id IN ($placeholders)
        AND r.offer_money > 0
        GROUP BY r.id
        HAVING COUNT(ros.skill_id) = 0
    ";

    $stmt_money = $conn->prepare($sql_money);
    $stmt_money->bind_param(str_repeat('i', count($requests_ids)), ...$requests_ids);
    $stmt_money->execute();
    $money_requests = $stmt_money->get_result()->fetch_all(MYSQLI_ASSOC);

    // 3. For money-only requests, get all skills
    if (!empty($money_requests)) {
        $sql_all_skills = "SELECT id AS offer_skill_id, name AS offer_skill_name FROM skills";
        $all_skills = $conn->query($sql_all_skills)->fetch_all(MYSQLI_ASSOC);
        
        foreach ($money_requests as $req) {
            $grouped_skills[$req['id']]['skills'] = array_map(function($skill) {
                return [
                    'offer_skill_id' => $skill['offer_skill_id'],
                    'offer_skill_name' => $skill['offer_skill_name']
                ];
            }, $all_skills);
            $grouped_skills[$req['id']]['is_linked'] = false;
            $grouped_skills[$req['id']]['offer_money'] = $req['offer_money'];
        }
    }
}

include '../sidebar.php';
include '../nav.php';
// include '../my_requests_controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests</title>
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

    <div class="content-wrapper">
    <!-- Marketplace Content -->
    <div class="container py-5">
        <!-- Search and Filter Section -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Find Request</h5>
                <div class="row g-3">
                    <!-- Search Bar -->
                    <div class="col-md-12 mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Requests..." id="searchInput">
                            <button class="btn btn-primary" type="button" id="searchButton">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills Grid -->
        <div class="row g-4" id="skillsGrid">
            <?php

            foreach($grouped_skills as $request_id => $grouped_skill) {
            ?>
                <!-- Skill Card 1 -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0"><?php
                                    if($grouped_skill['is_linked'] == 1){
                                        foreach( $grouped_skill['skills'] as $skill) {
                                            echo '<span class="skill-badge bg-primary text-white">
                                                '. htmlspecialchars($skill['offer_skill_name']). '
                                            </span>';
                                        }
                                    }
                                    else{
                                        echo '<span class="badge bg-success">
                                                <i class="bi bi-cash"></i> &nbsp;'. htmlspecialchars($grouped_skill['offer_money']) . ' EGP
                                            </span>';
                                    }
                                ?></h5>
                                    <?php
                                    if($grouped_skill['is_linked'] == 1){
                                        echo '<span class="badge bg-primary">Need Skill</span>';
                                    }
                                    else{
                                        echo '<span class="badge bg-success">Offer Money</span>';
                                    }
                                    ?>
                            </div>
                            <p class="card-text"><i>Your Note:</i> <br><?php
                                                    foreach($responses as $response) {
                                                        if($response['request_id'] == $request_id) {
                                                            echo $response['note'];
                                                        }
                                                    }
                                                ?>
                            </p>
                            <div class="skill-meta">
                                <div class="mb-2 text-muted small">
                                    <i class="bi bi-bar-chart-fill me-1"></i> 
                                    <?php
                                        foreach($responses as $response) {
                                            if($response['request_id'] == $request_id) {
                                                echo $response['created_at'];
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="mb-2 text-muted small">
                                    <?php
                                        foreach($responses as $response) {
                                            if($response['request_id'] == $request_id) {
                                                $status = $response['status'] ? 'success' : 'primary';
                                                $message = $response['status'] ? 'accepted' : 'Not yet';
                                                echo '<span class="badge bg-'. $status .'">'. $message .'</span>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                                foreach($responses as $response) { 
                                    if($response['request_id'] == $request_id) {?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <a href="http://localhost:8888/skill_swap/Responses/response_details.php?id=<?php echo $response['id']; ?>" class="btn btn-primary">View Details</a>
                                            </div>
                                        </div>
                            <?php
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    </div>

<?php
        include '../footer.php';
    ?>

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <!-- <script src="../js/marketplace.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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