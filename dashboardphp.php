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

$user = $_SESSION['userlogin'];
$usersetstable = "user_" . $user;

$setvalue = $_POST["setvalue"];
$setvaluetable = $usersetstable . "_set_" . $setvalue;

$titletochange = $_POST["title"];
$expensetochange = $_POST["expense"];
$datetimetochange = $_POST["datetime"];

$action = $_POST["display"];
$newsetname = $_POST["newsetname"];



if ($conn) {


    try {
        if (strlen($newsetname) >= 1 && strlen($newsetname) <= 255) {
            $newsettablename = $usersetstable . "_set_" . $newsetname;
            $sqlinsert = "INSERT INTO $usersetstable VALUES ('$newsetname',0);";
            $sqlcreate = "CREATE TABLE $newsettablename (title varchar(30) not null,expense int not null,date_time varchar(30),settled varchar(6));";
            if (mysqli_query($conn, $sqlcreate)) {
                mysqli_query($conn, $sqlinsert);
                echo "created set";
            } else {
                echo "set already exits";
            }
        } elseif ($newsetname) {
            echo "please fill the field with charachterlength between 1 to 255";
        } else if ($action == "deleteset") {
            $sql = "DELETE FROM $usersetstable WHERE setname='$setvalue'";
            if (mysqli_query($conn, $sql)) {
                $sql = "DROP TABLE $setvaluetable;";
                mysqli_query($conn, $sql);
                echo "successful:Deleted the set";
            } else {
                echo "unsuccessful:couldn't delete the set";
            }
        } else if ($titletochange && $expensetochange && $action == "updatedeletion") {
            $sql = "DELETE FROM $setvaluetable WHERE (title = '$titletochange') and (expense=$expensetochange);";
            if (mysqli_query($conn, $sql)) {
                echo "Deleted the item";
            } else {
                echo "couldn't delete the item";
            }
        } else if ($titletochange && $expensetochange && $action == "updatesettled") {
            $sql = "UPDATE  $setvaluetable SET settled = 'set' WHERE (title = '$titletochange') and (expense=$expensetochange);";
            if (mysqli_query($conn, $sql)) {
                echo "Settled the item";
            } else {
                echo "couldn't settle the item";
            }
        } else if ($titletochange && $expensetochange && $action == "updateaddition") {
            $sql = "INSERT INTO $setvaluetable VALUES ('$titletochange',$expensetochange,'$datetimetochange','notset');";
            if (mysqli_query($conn, $sql)) {
                echo "Added the item";
            } else {
                echo "couldn't add the item";
            }
        } else {

            if ($action == "displayaddset") {

                echo "<h3>Add a new set </h3>";
                echo "<input type='text' minlength='1' maxlength='255' required='true' placeholder='setname' id='inputset'><br>";
                echo "<br><input type='submit' value='add set'>";
                echo "<div id='dispsetaddindication'><br></div>";
                echo "<br><br>";
            } else if ($action == "displayusersets") {

                $sql = "SELECT setname FROM $usersetstable;";
                $usersetstabledata = mysqli_query($conn, $sql);
                $usersetstablerows = mysqli_num_rows($usersetstabledata);
                echo "<hr><h3>View ,Edit set</h3>";
                echo "<select name='users' id='list' onchange = 'displayusersettable(this.value)'>";
                echo    "<option value='select0' id='select0'>Select a group:</option>";
                $value = 1;
                for ($i = 0; $i < $usersetstablerows; $i++) {
                    $usersetsrowdata = mysqli_fetch_assoc($usersetstabledata);
                    $setname[$i] = $usersetsrowdata['setname'];
                    echo "<option value='select$value' id='select$value'>$setname[$i]</option>";
                    $value++;
                }
                echo "</select><br><br><hr>";
            } else if ($action == "displaytable") {

                $sql = "SELECT title,expense,date_time,settled FROM $setvaluetable;";
                $setvaluetabledata = mysqli_query($conn, $sql);
                $setvaluetablerows = mysqli_num_rows($setvaluetabledata);
                if ($setvaluetablerows == 0) {
                    echo "No expenses made so far<br>Use the add button to add expenses in this set";
                } else {
                    echo "<table><tr><th>Title</th><th>Expenses</th><th>date_time</th><th></th><th></th></tr>";

                    $value = 1;
                    $totalexpense = 0;
                    for ($i = 0; $i < $setvaluetablerows; $i++) {
                        $setvaluerowdata = mysqli_fetch_assoc($setvaluetabledata);
                        $title[$i] = $setvaluerowdata['title'];
                        $expense[$i] = $setvaluerowdata['expense'];
                        $datetime[$i] = $setvaluerowdata['date_time'];
                        $settle[$i] = $setvaluerowdata['settled'];
                        $totalexpense += $expense[$i];

                        echo "<tr value='row$value' id='row$value'>";

                        echo "<td>$title[$i]</td>";
                        echo "<td>$expense[$i]</td>";
                        echo "<td> $datetime[$i]</td>";
                        if ($settle[$i] == "set") {
                            echo "<td>settled</td>";
                        } else {
                            echo "<td><button class='settleitem'>settle it</button></td>";
                        }
                        echo "<td><button class='delitem'>Delete-item</button></td>";

                        echo "</tr>";
                        $value++;
                    }
                    echo "</table><br><br>";
                    echo "total expenses = " . $totalexpense;
                }
            } else if ($action == "displayaddtitleform") {
                echo "<h2><u>$setvalue</u></h2>";
                echo "<input type='button' value='Delete - set' class='delset'>";
                echo "<h3>Add expenses on this set</h3>";
                echo "<input type='text' minlength='1' maxlength='30' required='true' placeholder='title*' id='inputtitle'>&nbsp;&nbsp;&nbsp";
                echo "<input type ='number' required='true' placeholder='enter expenses*' step= '1' id='inputexpenses'>&nbsp;&nbsp;&nbsp";
                echo "<input type='text' maxlength='30' placeholder='date time  (optional)' id='inputdatetime'>
                <br>enter expense value =positive for obtained money (or) negative for lended money ";
                echo "<br><input type='submit' value='add expenses'>";
                echo "<div id='dispexpensesadd'><br></div>";
            } else {
                echo "something went wrong please reload the page";
            }
        }
    } catch (Exception $e) {
        echo "Error :couldn't connect to database" . $e->getMessage();
    }
} else {
    echo "couldn't connect to the database";
}
