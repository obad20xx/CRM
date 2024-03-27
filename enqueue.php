<?php
require 'db_connection.php'; // Use the database connection

function enqueueItem($conn, $item) {
    if (!empty($item)) {
        $stmt = $conn->prepare("INSERT INTO QueueItems (ClientData, Status) VALUES (?, 'waiting')");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_GET['item'])) {
    enqueueItem($conn, $_GET['item']);
}

$conn->close();
?>