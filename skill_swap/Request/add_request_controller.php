<?php
    session_start();
    include '../Register/connect.php';
    include '../Register/auth.php';

    if(isset($_POST['description']))
    {
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $offer_type = isset($_POST['offer_type']) ? $_POST['offer_type'] : [];
        $offer_money = isset($_POST['money_amount']) ? $_POST['money_amount'] : NULL;
        $offer_money = $offer_money === '' ? NULL : floatval($offer_money);

        $skillsneeded_json = $_POST['skillsneeded'];
        $skillsneeded_array = json_decode($skillsneeded_json, true);

        $skillOffer_json = isset($_POST['skillOffer']) ? $_POST['skillOffer'] : '[]';
        $skillOffer_array = json_decode($skillOffer_json, true);

        // Get user ID from session
        $user_id = $_SESSION['user_id'];
        
        // Insert the request
        $query = "INSERT INTO requests (user_id, description, offer_money, created_at) 
                  VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isd", $user_id, $description, $offer_money);
        
        if (!$stmt->execute()) {
            $_SESSION['error'] = "Failed to add request: " . $stmt->error;
            header("Location: add_request.php");
            exit();
        }
        
        // Get the inserted request ID
        $request_id = $stmt->insert_id;
        
        // Insert skills needed
        if (!empty($skillsneeded_array)) {
            foreach ($skillsneeded_array as $skill) {
                $skill_id = $skill['id'];
                
                $query2 = "INSERT INTO requests_needed_skills (request_id, skill_id) 
                           VALUES (?, ?)";
                           
                $stmt2 = $conn->prepare($query2);
                $stmt2->bind_param("ii", $request_id, $skill_id);
                
                if (!$stmt2->execute()) {
                    // Log error but continue
                    error_log("Failed to add needed skill: " . $stmt2->error);
                }
                
                $stmt2->close();
            }
        }
        
        // Insert offered skills if that option was selected
        if (in_array('skill', $offer_type) && !empty($skillOffer_array)) {
            foreach ($skillOffer_array as $skill) {
                $skill_id = $skill['id'];
                
                $query3 = "INSERT INTO requests_offer_skills (request_id, skill_id) 
                           VALUES (?, ?)";
                           
                $stmt3 = $conn->prepare($query3);
                $stmt3->bind_param("ii", $request_id, $skill_id);
                
                if (!$stmt3->execute()) {
                    // Log error but continue
                    error_log("Failed to add offered skill: " . $stmt3->error);
                }
                
                $stmt3->close();
            }
        }
        
        $_SESSION['success'] = "Request added successfully!";
        header("Location: my_requests.php");
        exit();
    }
    else {
        $_SESSION['error'] = "Invalid form submission";
        header("Location: add_request.php");
        exit();
    }
?>
