<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
    require('db.php');
    if (isset($_REQUEST['username'])) {
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($con, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $check = "SELECT * FROM users WHERE user='$username'";
        $result = mysqli_query($con, $check);
        if (mysqli_num_rows($result)>0) {
            echo "<div class='form'>
                  <h3>User exists!</h3><br/>
                  <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                  </div>";
        } else {
            $query    = "INSERT into `users` (user, password) VALUES ('$username', '$password')";
            $result   = mysqli_query($con, $query);
            if ($result) {
                echo "<div class='form'>
                      <h3>You are registered successfully.</h3><br/>
                      <p class='link'>Click here to <a href='login.php'>Login</a></p>
                      </div>";
            } else {
                echo "<div class='form'>
                      <h3>Required fields are missing.</h3><br/>
                      <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                      </div>";
            }
        }
    } else {
?>
    <form class="form" name="registration" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <label for="username">Username should not be more than 10 characters.</label><input type="text" class="login-input" name="username" placeholder="Username" required />
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="login.php">Click to Login</a></p>
    </form>
<?php
    }
?>
</body>
</html>