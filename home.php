<?php
session_start();

if (!isset($_SESSION['userlogin'])) {
    header("Location: index.php");
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION);
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Saira+Semi+Condensed&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="about.css">

</head>

<body>
    <nav id="navigation">
        <a class="logo" disabled><i class="fa fa-bitcoin"></i> Wallet Manager</a>
        <div id="nav-right">
            <a href="home.php" class="active"><i class=" fa fa-fw fa-home"></i>Home</a>
            <a href="dashboard.php" "><i class=" fa fa-credit-card"></i> Dashboard</a>
            <a href="home.php?logout=true" id="logoutlink">logout</a>
        </div>
        <div id="smallnav-right">
            <a id="smallnav"><i class="fa fa-bars"></i></a>
        </div>
    </nav>


    <div id="bodypart">
        <h2 id="welcome"></h2>
        <hr>
        <div id="disp">
            <p> View ,Add ,Delete all your accounts in the Dashboard tab</p>
            <br>
            <p>Keep track of all your expenses in the walletmanager website</p>
            <br>
            <p>Sets of headings for your convenience</p>
            <br>
            <p>In a set add view and delete the expense details separately</p>
            <br>
        </div>
    </div>

    <script>
        document.getElementById("smallnav").addEventListener("click", smallnav);

        function smallnav() {
            var x = document.getElementById("nav-right");
            if (x.style.display === "block") {
                x.style.display = "none";
            } else {
                x.style.display = "block";
            }
        }
    </script>
    <script src="home.js"></script>
</body>

</html>