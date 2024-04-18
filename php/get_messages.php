<?php
$conversationId = $_POST['conversation_id'] ?? '1'; // Default to conversation ID 1 if not provided

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://77.37.120.167:3000/api/v1/accounts/1/conversations/$conversationId/messages?after=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$headers = array();
$headers[] = 'api_access_token: AMSy6mwKbBvLHyQGpM89q23K';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
// Parse JSON response
$responseData = json_decode($result, true);

// Prepare HTML to display messages
$output = [];
if (!empty($responseData['payload'])) {
    foreach ($responseData['payload'] as $message) {
        $content = htmlspecialchars($message['content']);
        $senderName = htmlspecialchars($message['sender']['name'] ?? 'Unknown');
        $createdAt = date('Y-m-d H:i:s', $message['created_at']);
        $messageType = ($message['message_type'] === 0 ? 'Incoming' : 'Outgoing');
        $privacy = $message['private'] ? 'Private' : 'Public';

        // ($privacy, $messageType)
        $output[] = "<div class='message'>
                        <strong>$senderName:</strong>
                        <p>$content</p>
                        <small>Sent on $createdAt</small>
                     </div>";
    }
}

echo implode("", $output);
?>
