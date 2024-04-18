<?php
$contactIdentifier = $_POST['contactIdentifier'] ?? '1'; // Default to conversation ID 1 if not provided
$contactConversationID = $_POST['conversation_id'] ?? '0';
$message = $_POST['message'] ?? '';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://77.37.120.167:3000/public/api/v1/inboxes/amaeXyB9Gog3VssESbWHG7Bx/contacts/$contactIdentifier/conversations/$contactConversationID/messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['content' => $message]));

$headers = array();
$headers[] = 'Content-Type: application/json';
//$headers[] = 'api_access_token: AMSy6mwKbBvLHyQGpM89q23K';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

echo $result;
?>