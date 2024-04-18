<?php
$contactID = $_POST['contactID'] ?? '0'; // Default to conversation ID 0 if not provided
if($contactID === '0'){
    return;
}

// cURL setup
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://77.37.120.167:3000/api/v1/accounts/1/contacts/$contactID/conversations");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$headers = array();
$headers[] = 'api_access_token: AMSy6mwKbBvLHyQGpM89q23K'; // Replace 'YourApiTokenHere' with your actual API token
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

// Assuming the response is JSON
$responseData = json_decode($result, true);

$conversations = [];
if (isset($responseData['payload'])) {
    foreach ($responseData['payload'] as $conversation) {
        $meta = $conversation['meta'];
        if($meta['channel'] === 'Channel::Api'){
            $displayName = htmlspecialchars($meta['sender']['name']);
            $conversationId = $conversation['id'];
            $conversations[] = "<div class='conversation' data-id='$conversationId'>$conversationId-$displayName \n\n --- \n\n</div>";
        }
        
    }
}

$output = [
    'conversations' => $conversations
];
echo json_encode($output);
?>