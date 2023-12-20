<?php

use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;

session_start();
require __DIR__ . '/vendor/autoload.php';
require_once 'database.php';
require 'header.php';


// Get the chat room ID from the URL parameter
$chat_room_id = $_GET['id'];

// Get the chat room name and description from the database
$chat_room = get_chat_room_by_id($chat_room_id);
$chat_room_name = $chat_room['name'];
$chat_room_description = $chat_room['description'];

// Get the current user ID from the session
$user_id =  isset($_SESSION['user_id']) ? $_SESSION["user_id"] : '';

// Get the current user's username from the database


// Check if the user has joined the chat room
if (!hasUserJoinedChatRoom( $chat_room_id,$user_id) && isset($_SESSION['user_id'])) {
    // If the user has not joined the chat room, add them to the chat room
    add_user_to_chat_room($user_id, $chat_room_id);
}

?>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
    crossorigin="anonymous"
  />
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"
  ></script>
  <link href="./style.css" rel="stylesheet"/>
<div class="container">
    <h1><?php echo $chat_room_name; ?></h1>
    <p><?php echo $chat_room_description; ?></p>

    <div class="row">
        <div id="messages">
            <div id="messagesDiv">
                <?php
                $messages = get_chat_messages($chat_room_id);
                foreach ($messages as $message) {
                    $message_user = get_user_by_id($message['user_id']);
                    echo '<div class="alert alert-success">';
                    echo '<strong>' . $message_user['username'] . ':</strong>' . $message['content'];
                    echo '</div>';
                }

                ?>
            </div>
        </div>  
    </div>

    <?php if (isset($_SESSION['user_id'])) { ?>
        <form method="post" id="chat-form">
            <div class="row">
                <div>
                    <div class="form-group">
                        <label for="message_content">Message:</label>
                        <input type="text" class="form-control" id="message_content" name="message_content" required>
                    </div>
                </div>
            </div>

                <input type="submit" class="btn btn-dark my-2 buttonW"  id="sendbtn" value="Send"></button>
        </form>
    <?php } else {
        echo "<p>Please login to participate in the chat</p>";
    } ?>
</div>

<script>
    // Connect to the WebSocket server
    var ws = new WebSocket('ws://localhost:8800');

    // Handle incoming messages
    ws.onmessage = function(event) {
        // Decode the message from JSON
        var message = JSON.parse(event.data);
        const data = JSON.parse(message.data);
        // Check the message type

        switch (message.type) {
            case 'chat_message':
                // If the message is a chat message, add it to the message list
                var messageElement = document.createElement('div');
                messageElement.className="alert alert-success";
                messageElement.innerHTML = '<strong>' + data.username + ': </strong>' + data.content;
                document.getElementById('messages').appendChild(messageElement);
                console.log(messageElement.innerHTML);
                
                break;
            default:
                console.log('Unknown message type: ' + message.type);
                break;
        }

    };

    const sendbtn = document.getElementById("chat-form");
    sendbtn.addEventListener('submit', (event) => {
        event.preventDefault();
        const input = document.getElementById("message_content")
        const msg = input.value.trim();


        const message = {
            type: 'chat_message',
            data: JSON.stringify({
                username: "<?php echo $_SESSION['username']; ?>",
                user_id: <?php echo $_SESSION['user_id']; ?>,
                content: msg,
                timestamp: <?php echo time(); ?>,
                room_id: <?php echo $_GET['id']; ?>,
            })
        }

        ws.send(JSON.stringify(message));
        console.log(message)
        input.value = "";
        location.reload();
    })
</script>

<?php require_once 'footer.php'; ?>