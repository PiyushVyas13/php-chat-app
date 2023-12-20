<?php
session_start();

require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error_msg = '';
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $owner_id = $_SESSION['user_id'];

    if (empty($name) || empty($description)) {
        $error_msg = 'Please fill in all fields.';
    } else {
        $result = create_chat_room($name, $description, $owner_id);

        if ($result) {
            header('Location: chat.php?id=' . $result);
            exit;
        } else {
            $error_msg = 'An error occurred while creating the chat room.';
        }
    }
}
?>

<?php include_once 'header.php'; ?>
<link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <div class="div3">

       <div class="card shadow-lm p-3 ">
             <div class="card-header border-0 bg-transparent">
               <h3 class="mb-0">Create a Chat Room</h3>
             </div>
   <?php if (!empty($error_msg)) { ?>
       <div class="error"><?php echo $error_msg; ?></div>
   <?php } ?>
   
   <form method="post">
       <label calss="my-2" for="name">Name:</label>
       <input class="form-control" type="text" name="name" required>
   
       <label my-2 for="description">Description:</label>
       <textarea class="form-control" name="description" required></textarea>
   
       <input class="btn btn-dark my-2" type="submit" name="create" value="Create">
   </form>
   </div>
   </div>

<?php include_once 'footer.php'; ?>