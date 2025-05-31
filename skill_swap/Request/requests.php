<?php
include '../Register/connect.php';
session_start();

// Fetch all requests
$sql = "SELECT requests.*, users.name AS user_name FROM requests JOIN users ON requests.user_id = users.id";
$result = $conn->query($sql);

// Check if the form for new request is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  // Get the user ID from session
    $skill_needed = $_POST['skill_needed'];
    $description = $_POST['description'];
    $offer_money = $_POST['offer_money'] ?? NULL;
    $offer_skill = $_POST['offer_skill'] ?? NULL;
    $_SESSION['user_id'] = $user_id;
    

    // Insert the new request into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO requests (user_id, skill_needed, description, offer_money, offer_skill) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param($user_id, $skill_needed, $description, $offer_money, $offer_skill);

    if ($stmt->execute()) {
        echo "<script>alert('Request created successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="requests.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Swap - Requests</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Requests</h2>

    <!-- Display existing requests -->
    <div class="requests-list">
        <?php while($row = $result->fetch_assoc()) { ?>
            <div class="request-card">
                <h3>Skill Needed: <?php echo $row['skill_needed']; ?></h3>
                <p><strong>Requested By:</strong> <?php echo $row['user_name']; ?></p>
                <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                <p><strong>Offer:</strong> 
                    <?php 
                        if ($row['offer_money']) {
                            echo "💵 " . $row['offer_money'] . " EGP";
                        } 
                        if ($row['offer_skill']) {
                            echo " 🔄 " . $row['offer_skill'];
                        }
                    ?>
                </p>
                <!-- Interaction buttons -->
                <button class="money-button">💵 Want Money</button>
                <button class="skill-button">🔄 Want Skill</button>
                <button class="both-button">💵🔄 Want Both</button>
            </div>
        <?php } ?>
    </div>

    <!-- Form for creating a new request -->
    <h3>Create a New Request</h3>
    <form method="POST" action="">
        <input type="text" name="skill_needed" placeholder="Skill Needed (e.g., Python, Flutter)" required><br>
        <textarea name="description" placeholder="Describe your request..." required></textarea><br>
        <input type="number" name="offer_money" placeholder="Offer Money (optional)" step="0.01"><br>
        <input type="text" name="offer_skill" placeholder="Offer Skill (optional)"><br>
        <button type="submit">Submit Request</button>
    </form>
</body>
</html>
