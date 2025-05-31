<?php
include '../Register/connect.php';
include '../Register/auth.php';

$response_id = intval($_GET['response_id']);
// Get response details
$sql = "SELECT r.*, u.name as responder_name, u.email as responder_email 
        FROM responds r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $response_id);
$stmt->execute();
$result = $stmt->get_result();
$response = $result->fetch_assoc();
$user_id = $response['user_id'];


$sql = "UPDATE responds SET status = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $response_id);
$stmt->execute();

if($response['response_type'] == 'skill' || $response['response_type'] == 'both')
{
    $request_id = $response['request_id'];
    
    // Get needed skills from the main request
    $sql_needed = "SELECT s.id, s.name 
                FROM requests_needed_skills rns 
                JOIN skills s ON rns.skill_id = s.id 
                WHERE rns.request_id = ?";
    $stmt_needed = $conn->prepare($sql_needed);
    $stmt_needed->bind_param("i", $request_id);
    $stmt_needed->execute();
    $needed_skills_for_the_main_request_offered_skills_for_the_secondery_request = $stmt_needed->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get offered skills for the main request
    $sql_offered = "SELECT s.id, s.name 
                    FROM requests_offer_skills ros 
                    JOIN skills s ON ros.skill_id = s.id 
                    WHERE ros.request_id = ?";
    $stmt_offered = $conn->prepare($sql_offered);
    $stmt_offered->bind_param("i", $request_id);
    $stmt_offered->execute();
    $offered_skills_for_the_main_request_needed_skills_for_the_secondery_request = $stmt_offered->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Make The new request
    $query = "INSERT INTO requests (user_id, description, offer_money, created_at) 
                  VALUES (?, 'Exchange of skills', NULL, CURRENT_TIMESTAMP)";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $secondery_request_id = $stmt->insert_id;
    $stmt->close();

    // Insert skills needed
    foreach ($offered_skills_for_the_main_request_needed_skills_for_the_secondery_request as $skill) {
        $skill_id = $skill['id'];
        
        $query2 = "INSERT INTO requests_needed_skills (request_id, skill_id) 
                VALUES (?, ?)";
                
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("ii", $secondery_request_id, $skill_id);
        
        if (!$stmt2->execute()) {
            // Log error but continue
            error_log("Failed to add needed skill: " . $stmt2->error);
        }
        
        $stmt2->close();
    }
    
    // Insert offered skills
    foreach ($needed_skills_for_the_main_request_offered_skills_for_the_secondery_request as $skill) {
    $skill_id = $skill['id'];

    $query3 = "INSERT INTO requests_offer_skills (request_id, skill_id) 
                VALUES (?, ?)";
                
    $stmt3 = $conn->prepare($query3);
    $stmt3->bind_param("ii", $secondery_request_id, $skill_id);

    if (!$stmt3->execute()) {
        // Log error but continue
        error_log("Failed to add offered skill: " . $stmt3->error);
    }

    $stmt3->close();
    }

    // Insert Main Request ID

    $query4 = "INSERT INTO main_request (main_request_id, request_id) 
                VALUES (?, ?)";
                
    $stmt4 = $conn->prepare($query4);
    $stmt4->bind_param("ii", $request_id, $secondery_request_id);
    $stmt4->execute();
    $stmt4->close();
    header("Location: my_requests.php");
    exit();
}
echo"<pre>"; print_r($response);die();
?>