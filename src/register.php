<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate user inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user details into database
        if (get_user_by_username($username)) {
            $error = "A user with given username already exists.";
        } else {
            $result = registerUser($username, $email, $hashed_password);
            if ($result === true) {
                // Redirect to login page
                header('Location: login.php');
                exit;
            } else {
                $error = "An error occurred while registering. Please try again later.";
            }
        }
    }
} else {
    $error = "";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<link href="./style.css" rel="stylesheet"/>
</head>

<body>
<?php include_once "header.php"?>


<div class="div2">
    <div class="registerForm">

        <div class="card shadow-lm p-3">
                  <div class="card-header border-0 bg-transparent">
                    <h3 class="mb-0">Register</h3>
                  </div>
            
            <?php if (!empty($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="register.php" method="post">
                <label for="username">Username:</label>
                <input class="form-control" type="text" id="username" name="username" required><br>
        
                <label for="email">Email:</label>
                <input class="form-control" type="email" id="email" name="email" required><br>
        
                <label for="password">Password:</label>
                <input class="form-control" type="password" id="password" name="password" required><br>
        
                <input class="btn btn-dark" type="submit" value="Register">
            </form>
            </div>
    </div>

</div>
</body>

</html>