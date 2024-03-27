<?php
require 'db_connection.php'; // Use the database connection

function dequeueItem($conn) {
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
}

dequeueItem($conn);


$conn->close();
?>