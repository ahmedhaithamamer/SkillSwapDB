<?php
include 'connect.php';

header('Content-Type: application/json');

$sql = "SELECT id, name FROM skills ORDER BY name ASC";
$result = $conn->query($sql);

$skills = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $skills[] = [
            "id" => $row['id'],
            "value" => $row['name']
        ];
    }
}

echo json_encode($skills);
?>
