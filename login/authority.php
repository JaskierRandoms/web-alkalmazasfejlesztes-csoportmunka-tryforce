<?php
    require_once "db_connect.php";

    function phpAlert($msg, $php) {
        echo '<script type="text/javascript">alert("' . $msg . '")</script>';
        echo '<script type="text/javascript">window.location = ("' . $php . '")</script>';
    }
    
        $sql = "UPDATE users SET admin=? WHERE user=?";
        //$sql2 = "UPDATE users SET admin=1 WHERE user=?";

        $username = $_POST['username'];
        $status = $_POST['status'];
        $admin=1;

        if ($status == "Admin") {
            if($stmt = mysqli_prepare($con, $sql)){
                mysqli_stmt_bind_param($stmt, "is", $admin, $username);
                if (mysqli_stmt_execute($stmt)) {
                    phpAlert("Sikeres jogosultság váltás!!", "admins.php");
                }
                else {
                    phpAlert("Sikertelen jogosultság váltás!!", "admins.php");
                }
            }
        }
        elseif($status == "Felhasználó") {
            $admin=0;
            if($stmt = mysqli_prepare($con, $sql)){
                mysqli_stmt_bind_param($stmt, "is", $admin, $username);
                if (mysqli_stmt_execute($stmt)) {
                    phpAlert("Sikeres jogosultság váltás!!", "admins.php");
                }
                else {
                    phpAlert("Sikertelen jogosultság váltás!!", "admins.php");
                }
            }
        }
    
    