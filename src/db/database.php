<?php
// Define the database connection details
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'chat_room';

// Connect to the database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * Function to fetch the details of a chat room by its ID
 * @param int $room_id The ID of the chat room
 * @return array|null The chat room details as an associative array, or null if not found
 */
function get_chat_room_details($room_id)
{
    global $conn;

    $sql = "SELECT * FROM chat_rooms WHERE id = $room_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        return null;
    }

    return mysqli_fetch_assoc($result);
}

function create_chat_room($name, $desc, $owner_id)
{
    global $conn;

    $query = "INSERT INTO rooms (name, description, created_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $name, $desc, $owner_id);

    if ($stmt->execute()) {
        $chat_room_id = $stmt->insert_id;
        add_user_to_chat_room($owner_id, $chat_room_id);
        return $chat_room_id;
    } else {
        return false;
    }
}

function get_chat_room_by_id($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function add_user_to_chat_room($oid, $cr_id)
{
    global $conn;

    $query = "INSERT INTO room_users (user_id, room_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $oid, $cr_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Function to fetch all chat rooms
 * @return array An array of chat rooms as associative arrays
 */
function get_all_chat_rooms()
{
    global $conn;

    $sql = "SELECT * FROM rooms";
    $result = mysqli_query($conn, $sql);

    $chat_rooms = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $chat_rooms[] = $row;
    }

    return $chat_rooms;
}

function get_user_by_username($username)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function get_user_by_id($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function get_chat_rooms($num_rooms)
{
    global $conn;

    $sql = "SELECT * FROM rooms ORDER BY created_at DESC LIMIT $num_rooms";
    $result = mysqli_query($conn, $sql);

    return $result;
}

/**
 * Function to fetch the messages for a chat room by its ID
 * @param int $room_id The ID of the chat room
 * @return array An array of chat messages as associative arrays
 */
function get_chat_messages($room_id)
{
    global $conn;

    $sql = "SELECT * FROM messages WHERE room_id = $room_id ORDER BY created_at ASC";
    $result = mysqli_query($conn, $sql);

    $chat_messages = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $chat_messages[] = $row;
    }

    return $chat_messages;
}

/**
 * Function to insert a new chat message into the database
 * @param int $room_id The ID of the chat room
 * @param string $username The username of the user who sent the message
 * @param string $message The message content
 * @return bool True if the message was inserted successfully, false otherwise
 */
function insert_chat_message($room_id, $user_id, $message)
{
    global $conn;

    $sql = "INSERT INTO messages (room_id, user_id, content) VALUES ($room_id, $user_id, '$message')";
    $result = mysqli_query($conn, $sql);

    return $result;
}

/**
 * Registers a new user
 *
 * @param string $username The username of the user
 * @param string $password The password of the user
 * @param string $email The email address of the user
 * @return bool Returns true if the user was successfully registered, false otherwise
 */
function registerUser($username, $email, $hashedPassword)
{
    global $conn;

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        return false;
    }

    // Hash the password
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);
    $result = $stmt->execute();

    return $result;
}

/**
 * Check if a user has joined a chat room
 *
 * @param int $chatRoomId ID of the chat room
 * @param int $userId ID of the user
 * @return bool True if the user has joined the chat room, false otherwise
 */
function hasUserJoinedChatRoom($chatRoomId, $userId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM `room_users` WHERE `room_id` = ? AND `user_id` = ?");
    $stmt->bind_param("ii", $chatRoomId, $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}
