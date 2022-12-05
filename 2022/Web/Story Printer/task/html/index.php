<?php
include("auth_session.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Story Printer</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="form">
        <p>Hey, <?php echo $_SESSION['username']; ?>!</p>
        <p>Welcome to the Story Printer service.</p>
        <p>It is secure and convenient tool which puts your stories into HTML table and after that "prints" them to PDF.</p>
        <p>Unfortunately, developers are still working on that service, so right now only Admin user can use the service.</p>
        <p><a href="html_to_pdf.php">Only for Admin user!</a></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>