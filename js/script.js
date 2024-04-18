// put contact details static
var contentPhone = '+201000000557';
var contentName = 'qwe';
var contentEmail = 'qwe@qwe.com';
var contactID = 0;
var contactIdentifier = 0;
var contactPubsubToken = 0;
var conversationId = -1;
var inboxIdentifier = 'amaeXyB9Gog3VssESbWHG7Bx';

// get contact details from session
// if ($_SESSION['LoggedIn'] === true
//     &&
//     isset($_SESSION['Full_Name'])
//     &&
//     isset($_SESSION['Mobile'])
//     &&
//     isset($_SESSION['Email'])
// ) {
//     contentName = '<?php echo $_SESSION["Full_Name"] ; ?>';

//     contentPhone = '<?php echo $_SESSION["Mobile"]; ?>';

//     contentEmail = '<?php echo $_SESSION["Email"]; ?>';

// }

$(document).ready(function () {
    // Set up the contact
    setUpContact(contentPhone)
    loadConversations(contactID);

    // Correctly attach click event to dynamically loaded conversation divs
    $("#conversation-list").on("click", "div.conversation", function () {
        $('.conversation').removeClass('selected-conversation'); // Remove class from all conversations
        $(this).addClass('selected-conversation'); // Add class to the clicked conversation

        conversationId = $(this).data("id");
        $('#messages').data('current-conversation-id', conversationId); // Set the current conversation ID
        loadMessages(conversationId);
    });

    // Execute loadMessages() every 10 seconds
    setInterval(function () {
        var conversationId = $('#messages').data('current-conversation-id');
        if (conversationId !== undefined) {
            loadMessages(conversationId);
        }
    }, 10000);

});

function scrollToBottom() {
    var messages = $('#messages');
    messages.scrollTop(messages.prop("scrollHeight"));
}

function setUpContact(contentPhone) {

    // Prepare data for new contact
    var contactData = {
        email: contentEmail,
        name: contentName,
        phone_number: contentPhone // And this one too
    };

    // Use jQuery for AJAX request
    $.ajax({
        type: "POST",
        url: "http://77.37.120.167:3000/public/api/v1/inboxes/"+inboxIdentifier+"/contacts",
        contentType: "application/json",
        data: JSON.stringify(contactData),
        async: false,
        success: function (responseData) {
            contactIdentifier = responseData.source_id;
            contactPubsubToken = responseData.pubsub_token;
            contactID = responseData.id;
        },
        error: function () {
            console.error("Failed to create contact in Chatwoot");
        }
    });

}

function loadConversations(contactID) {
    $.ajax({
        url: 'php/get_conversations.php',
        method: 'POST', // Assuming that your server expects POST requests
        data: { contactID: contactID }, // Ensure your PHP handles this action if needed
        dataType: 'json', // The response type
        success: function (response) {
            $('#conversation-list').html(response.conversations);
        },
        error: function (xhr, status, error) {
            console.error("Error loading conversations: " + xhr.responseText);
        }
    });
}

function loadMessages(conversationId) {
    if (conversationId === -1)
        return;
    $.ajax({
        url: 'php/get_messages.php',
        method: 'POST',
        data: { conversation_id: conversationId },
        success: function (response) {
            $('#messages').html(response);
            scrollToBottom();
        },
        error: function (xhr, status, error) {
            console.error("Error loading messages: " + xhr.responseText);
        }
    });

}

function sendMessage() {
    var message = $('#message-input').val();
    var conversationId = $('#messages').data('current-conversation-id'); // Retrieve the current conversation ID
    if (!conversationId) {
        alert('No conversation selected.');
        return;
    }
    $.ajax({
        url: 'php/send_message.php', // Correct the URL if necessary
        method: 'POST',
        data: { contactIdentifier: contactIdentifier, conversation_id: conversationId, message: message },
        success: function (response) {
            $('#message-input').val('');
            loadMessages(conversationId);
            scrollToBottom();
        },
        error: function (xhr, status, error) {
            console.error("Error sending message: " + xhr.responseText);
        }
    });
}

function setUpConversation() {
    $.ajax({
        type: "POST",
        url: "http://77.37.120.167:3000/public/api/v1/inboxes/amaeXyB9Gog3VssESbWHG7Bx/contacts/" + contactIdentifier + "/conversations",
        async: false,
        success: function (response) {
            loadConversations(response.contact.id);
        },
        error: function (xhr, status, error) {
            console.error("Error creating conversation: " + xhr.responseText);
        }
    });

}


