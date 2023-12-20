<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Chat Room App</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</head>

<body>
    <!-- <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION["username"])) : ?>
           <a href="logout.php">Logout </a>
        <?php else : ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register </a>
        <?php endif; ?>
    </nav> -->
    

    <nav class="navbar navbar-expand-lg bg-success">
  <div class="container-fluid">
  <a class="nav-link" href="index.php">Home</a>
    
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <?php if(isset($_SESSION['user_id'])){?>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout </a>
            
        </li>
        <li class="nav-item">
        <a class="nav-link mx-3" href="create-room.php">Create room  </a>
        </li>
        <?php } else{?>
        <li class="nav-item mx-2">

            <a class="nav-link" href="login.php">Login</a>
            
            
        </li>

        <li class="nav-item">
        <a class="nav-link" href="register.php">Register </a>
    </li>
    <?php } ?>
        
        
    </ul>
      
    </div>
  </div>
</nav>




    <div class="div1">