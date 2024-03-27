<?php
error_reporting(0);
@ini_set('display_errors', 0);

$serverName = "127.0.0.1:3307"; // Or your MySQL server host
$username = "root"; // Your MySQL username
$password = "P@ssw0rd"; // Your MySQL password
$dbName = "queue"; // Your MySQL database name

// Establishes the connection
$conn = new mysqli($serverName, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_GET['action'] ?? '';
$status = $_GET['status'] ?? ''; // 'waiting' or 'processing'

switch ($action) {
    case 'enqueue':
        $item = $_GET['item'] ?? '';
        if (!empty($item)) {
            $stmt = $conn->prepare("INSERT INTO QueueItems (ClientData, Status) VALUES (?, 'waiting')");
            $stmt->bind_param("s", $item);
            $stmt->execute();
            $stmt->close();
        }
        break;
    case 'dequeue':
        $conn->begin_transaction();
        try {
            $result = $conn->query("SELECT ItemId FROM QueueItems WHERE Status = 'waiting' ORDER BY EnqueueTime ASC LIMIT 1 FOR UPDATE");
            if ($row = $result->fetch_assoc()) {
                $itemId = $row['ItemId'];
                $conn->query("UPDATE QueueItems SET Status = 'processing' WHERE ItemId = $itemId");
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
        break;
    case 'list':
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
        break;
}

$query = "SELECT * FROM QueueItems WHERE Status = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        header('Content-Type: application/json');
    echo json_encode($items);
        $stmt->close();

// Close connection
$conn->close();
?>