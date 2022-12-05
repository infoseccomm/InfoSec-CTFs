<?php
    session_start();
    if(!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    } else {
        if (!($_SESSION["username"]=="Admin")) {
            header("Location: index.php");
            exit();
        }
    }
?>
<html>
<head>
<link href="style.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<div id="wrapper">

<div id="html_div">
 <form action="generate_pdf.php" method="post">
  <input type="text" name="title" placeholder="Title">
  <br>
  <input type="text" name="author" placeholder="Author">
  <br>
  <input type="text" name="date" placeholder="Date">
  <br>
  <input type="text" name="text" placeholder="Text">
  <br>
  <input type="submit" name="submit_val" value="GENERATE PDF">
 </form>
</div>

</div>
</body>
</html>

