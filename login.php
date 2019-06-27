<?php
session_start();

$servername = "localhost";
$sqluser = "Your_user";
$sqlpassword = "Your_password";
$databasename = "walletmanager";
$conn = mysqli_connect($servername, $sqluser, $sqlpassword, $databasename);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];


if ($email) {
    $hashpassword = password_hash($password, PASSWORD_BCRYPT);
    $sqlins = "INSERT INTO userdetails VALUES ('$username','$email','$hashpassword');";
    try {
        if (strlen($username) > 4 && strlen($email) > 4 && strlen($password) > 7) {
            if ($conn) {
                $sql = "CREATE TABLE user_$username (
                        setname varchar(30) not null,
                        moneyspent INT unsigned not null
                        );";
                if (mysqli_query($conn, $sql)) {
                    mysqli_query($conn, $sqlins);
                    echo "User successfully created";
                } else {
                    echo "unable to create user  <br> user already exists";
                }
            } else {
                echo "unable to create user  <br>" . mysqli_error($conn);
            }
        } else {
            echo "Fill all the fields with required lengths";
        }
    } catch (Exception $e) {
        echo "please fill all fields <br> " . $e->getMessage();
    }
} else {

    $sql = "SELECT username,email,password FROM userdetails;";
    try {
        if (strlen($username) > 4 && strlen($password) > 7) {
            $userdetailstable = mysqli_query($conn, $sql);
            $userdetailsrows = mysqli_num_rows($userdetailstable);
            $loginsuccess = 0;

            for ($i = 0; $i < $userdetailsrows; $i++) {
                $userdetailsrowdata = mysqli_fetch_assoc($userdetailstable);
                $userdetailsusername[$i] = $userdetailsrowdata['username'];
                $userdetailsemail[$i] = $userdetailsrowdata['email'];
                $userdetailspassword[$i] = $userdetailsrowdata['password'];
                if (($username == $userdetailsusername[$i] || $username == $userdetailsemail[$i]) && password_verify($password, $userdetailspassword[$i])) {
                    $_SESSION['userlogin'] = $userdetailsusername[$i];
                    $loginsuccess = 1;
                }
            }
            if ($loginsuccess == 1) {
                echo "logging in";
            } else {
                echo "invalid user credentials";
            }
        } else {
            echo "invalid user credentials";
        }
    } catch (Exception $e) {
        echo "please fill all the fields correctly <br> " . $e->getMessage();
    }
}
