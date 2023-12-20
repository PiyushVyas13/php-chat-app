<?php
session_start();
if (isset($_SESSION["username"])) {
    echo "HEllo";
    unset($_SESSION['username']);
    unset($_SESSION['user_id']);

    header("Location: index.php");
    exit;
}
