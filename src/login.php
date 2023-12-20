<?php
session_start();

require_once "database.php";

// Check if user is already logged in
if (isset($_SESSION["username"])) {
    // $prev = $SERVER['HTTP_REFERER'];
    header("location:index.php");
    exit;
}

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if username and password are valid
    if (empty($username_err) && empty($password_err)) {

        $user = get_user_by_username($username);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $user["id"];

                // Redirect to index page
                header("location: index.php");
                exit;
            } else {
                // Display an error message if password is not valid
                $password_err = "Invalid password.";
            }
        } else {
            // Display an error message if username does not exist
            $username_err = "User not found.";
        }
    }
}
?>

<?php include "header.php"; ?>

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


<div class="loginForm">

    <form method="post">
    <div class="card shadow-lm p-3 ">
          <div class="card-header border-0 bg-transparent">
            <h3 class="mb-0">Login</h3>
          </div>
    
            <div class="form-group mb-3 ">
                <label for="username">Username:</label>
                <input class="form-control" type="text" name="username" id="username" value="<?php echo $username; ?>">
                <span class="error "><?php echo $username_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password:</label>
                <input class="form-control" type="password" name="password" id="password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <button class="btn btn-success" type="submit">Login</button>
            </div>
        </div>
    </form>
</div>

<?php include "footer.php"; ?>