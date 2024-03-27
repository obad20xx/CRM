<?php
require 'db_connection.php'; // Use the database connection

$status = $_GET['status'] ?? 'waiting'; // Default to 'waiting' if not specified

$query = "SELECT * FROM QueueItems WHERE Status = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
echo json_encode($items);
$stmt->close();

$conn->close();
?>