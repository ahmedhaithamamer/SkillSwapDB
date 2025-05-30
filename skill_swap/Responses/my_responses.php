<?php
include '../Register/connect.php';
include '../Register/auth.php';

// Get all responses for the current user
$sql = "SELECT r.id, r.request_id, r.user_id, r.note, r.created_at, u.name as responder_name, u.email as responder_email 
        FROM responds r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$responses = [];
while ($row = $result->fetch_assoc()) {
    $responses[$row['id']] = $row;
}

// Process responses and collect IDs in one loop
$response_ids = array_keys($responses);

// Get request details for each response
$request_ids = array_column($responses, 'request_id');
$sql = "SELECT id, description, offer_money 
        FROM requests 
        WHERE id IN (".implode(',', array_fill(0, count($request_ids), '?')).")";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($request_ids)), ...$request_ids);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[$row['id']] = $row;
}

// Initialize empty arrays (avoids undefined variable warnings)
$grouped_skills = [];

// Collect IDs of skills needed for each request
$sql = "SELECT request_id, skill_id 
        FROM requests_needed_skills 
        WHERE request_id IN (".implode(',', array_fill(0, count($request_ids), '?')).")";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($request_ids)), ...$request_ids);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $grouped_skills[$row['request_id']][] = $row['skill_id'];
}

// Get skill names for each request
$skill_ids = array_unique(array_merge(...array_values($grouped_skills)));
$sql = "SELECT id, name 
        FROM skills 
        WHERE id IN (".implode(',', array_fill(0, count($skill_ids), '?')).")";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($skill_ids)), ...$skill_ids);
$stmt->execute();
$result = $stmt->get_result();

$skills = [];
while ($row = $result->fetch_assoc()) {
    $skills[$row['id']] = $row['name'];
}

// Process responses and requests
foreach ($responses as $response_id => $response) {
    $request = $requests[$response['request_id']];
    $response['request_description'] = $request['description'];
    $response['request_offer_money'] = $request['offer_money'];
    $response['request_skills'] = [];
    foreach ($grouped_skills[$request['id']] as $skill_id) {
        $response['request_skills'][] = $skills[$skill_id];
    }
    $responses[$response_id] = $response;
}

?>
