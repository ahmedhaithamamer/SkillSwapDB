<?php
include '../Register/connect.php';
include '../Register/auth.php';
include '../sidebar.php';
include '../nav.php';

// Check if request ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Request ID is required'); window.location.href='my_requests.php';</script>";
    exit();
}

$response_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'];

// Get response details
$sql = "SELECT r.* 
        FROM responds r 
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $response_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Request not found'); window.location.href='my_requests.php';</script>";
    exit();
}

$response = $result->fetch_assoc();
$response_id = $response['id'];
$request_id = $response['request_id'];
$status = $response['status'];

// Get request details
$sql2 = "SELECT r.*, u.name as requester_name, u.email as requester_email 
        FROM requests r 
        JOIN users u ON r.user_id = u.id
        WHERE r.id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $request_id);
$stmt2->execute();
$result = $stmt2->get_result();
$request = $result->fetch_assoc();

// Get Main Request details
$sqlm = "SELECT main_request_id 
         FROM main_request 
         WHERE request_id = ?";
$ss = $conn->prepare($sqlm);
$ss->bind_param("i", $request_id);
$ss->execute();
$result = $ss->get_result();
$row = $result->fetch_assoc();
$main_request_id = NULL;
if(!empty($row['main_request_id'])) {
    $main_request_id = $row['main_request_id'];
    // echo "<pre>"; print_r($main_request_id);die();
}

// Get needed skills
$sql_needed = "SELECT s.id, s.name 
               FROM requests_needed_skills rns 
               JOIN skills s ON rns.skill_id = s.id 
               WHERE rns.request_id = ?";
$stmt_needed = $conn->prepare($sql_needed);
$stmt_needed->bind_param("i", $request_id);
$stmt_needed->execute();
$needed_skills = $stmt_needed->get_result()->fetch_all(MYSQLI_ASSOC);

// Get offered skills
$sql_offered = "SELECT s.id, s.name 
                FROM requests_offer_skills ros 
                JOIN skills s ON ros.skill_id = s.id 
                WHERE ros.request_id = ?";
$stmt_offered = $conn->prepare($sql_offered);
$stmt_offered->bind_param("i", $request_id);
$stmt_offered->execute();
$offered_skills = $stmt_offered->get_result()->fetch_all(MYSQLI_ASSOC);

// Get responses to this request
$sql_responses = "SELECT r.*, u.name as responder_name, u.email as responder_email 
                 FROM responds r 
                 JOIN users u ON r.user_id = u.id 
                 WHERE r.request_id = ? 
                 ORDER BY r.created_at DESC";
$stmt_responses = $conn->prepare($sql_responses);
$stmt_responses->bind_param("i", $request_id);
$stmt_responses->execute();
$responses = $stmt_responses->get_result()->fetch_all(MYSQLI_ASSOC);

// Check if current user has already responded
$has_responded = false;
foreach ($responses as $response) {
    if ($response['user_id'] == $current_user_id) {
        $has_responded = true;
        break;
    }
}

// Process new response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_response'])) {
    $message = mysqli_real_escape_string($conn, $_POST['response_message']);
    $response_type = mysqli_real_escape_string($conn, $_POST['response_type']);
    
    $sql_insert = "INSERT INTO responses (request_id, user_id, message, response_type, created_at) 
                  VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iiss", $request_id, $current_user_id, $message, $response_type);
    
    if ($stmt_insert->execute()) {
        // Refresh the page to show the new response
        echo "<script>window.location.href='request_details.php?id=" . $request_id . "&success=1';</script>";
        exit();
    } else {
        $error_message = "Failed to submit response: " . $stmt_insert->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        .request-container {
            max-width: 900px;
            margin: 30px auto;
        }
        .skill-badge {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 5px 5px 0;
            border-radius: 20px;
            font-size: 14px;
        }
        .response-card {
            border-left: 4px solid #0d6efd;
            margin-bottom: 20px;
        }
        .response-card.owner {
            border-left-color: #198754;
        }
        .response-meta {
            font-size: 14px;
            color: #6c757d;
        }
        .response-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="container py-5">
            <div class="request-container">
                <!-- Success message -->
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Response submitted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Error message -->
                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Back button -->
                <div class="mb-4">
                    <a href="my_responses.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to My Responses
                    </a>
                </div>
                
                <!-- Request Details Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Request Details</h4>
                        <span class="badge bg-<?php echo $status ? 'success' : 'primary'; ?>" style="font-size: 14px;">
                            <?php echo $status ? 'accepted' : 'Not yet'; ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($request['description']); ?></h5>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6><i class="bi bi-person-fill"></i> Requested by:</h6>
                                <p><?php echo htmlspecialchars($request['requester_name']); ?></p>
                                
                                <h6><i class="bi bi-calendar-event"></i> Created on:</h6>
                                <p><?php echo date('F j, Y, g:i a', strtotime($request['created_at'])); ?></p>
                                
                                <h6><i class="bi bi-book"></i> Your Note:</h6>
                                <p style="font-weight: bold;"><?php echo nl2br(htmlspecialchars($response['note'])); ?></p>
                                
                                <?php if ($main_request_id): ?>
                                <h6>Main Request:
                                <a href="request_details.php?id=<?php echo $main_request_id; ?>"><?php echo $main_request_id; ?></a>
                                </h6>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <h6><i class="bi bi-tools"></i> Skills Needed:</h6>
                                <div>
                                    <?php if (empty($needed_skills)): ?>
                                        <p class="text-muted">No specific skills requested</p>
                                    <?php else: ?>
                                        <?php foreach ($needed_skills as $skill): ?>
                                            <span class="skill-badge bg-primary text-white">
                                                <?php echo htmlspecialchars($skill['name']); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <h6 class="mt-3"><i class="bi bi-gift"></i> Offering:</h6>
                                <div>
                                    <?php if ($request['offer_money'] > 0): ?>
                                        <div class="mb-2">
                                            <span class="badge bg-success">
                                                <i class="bi bi-cash"></i> <?php echo htmlspecialchars($request['offer_money']); ?> EGP
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($offered_skills)): ?>
                                        <?php foreach ($offered_skills as $skill): ?>
                                            <span class="skill-badge bg-success text-white">
                                                <?php echo htmlspecialchars($skill['name']); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php if ($request['offer_money'] <= 0 && empty($offered_skills)): ?>
                                        <p class="text-muted">No specific offer</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>
    
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>