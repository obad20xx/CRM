<?php
error_reporting(0);
@ini_set('display_errors', 0);

// File to store the queue
$file = 'queue.txt';

// Function to modify the queue
function modifyQueue($action, $file, $item = null) {
    $queue = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

    if ($action == 'enqueue' && $item !== null) {
        array_push($queue, $item); // Add item to the end
    } elseif ($action == 'dequeue' && count($queue) > 0) {
        array_shift($queue); // Remove the first item
    }

    // Write the updated queue back to the file with an exclusive lock
    $fp = fopen($file, 'c+');
    if (flock($fp, LOCK_EX)) { // Obtain an exclusive lock
        ftruncate($fp, 0); // Truncate the file
        fwrite($fp, implode("\n", $queue)); // Write the updated queue
        fflush($fp); // Flush output before releasing the lock
        flock($fp, LOCK_UN); // Release the lock
    }
    fclose($fp);

    return $queue;
}

// Process the action
$action = $_GET['action'] ?? '';
$item = $_GET['item'] ?? null;

// Use the modifyQueue function based on the action
$queue = modifyQueue($action, $file, $item);


// Always return the current queue
header('Content-Type: application/json');
echo json_encode(file($file, FILE_IGNORE_NEW_LINES));

?>