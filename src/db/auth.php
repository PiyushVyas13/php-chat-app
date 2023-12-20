<?php
session_start();

/**
 * Function to check if a user is logged in
 * @return bool True if the user is logged in, false otherwise
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Function to log in a user
 * @param string $username The username of the user
 * @param string $password The password of the user
 * @return bool True if the login was successful, false otherwise
 */
function login_user($username, $password)
{
    global $conn;

    $sql = "SELECT id, password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hash = $row['password'];

        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $row['id'];
            return true;
        }
    }

    return false;
}

/**
 * Function to log out a user
 */
function logout_user()
{
    unset($_SESSION['user_id']);
}
