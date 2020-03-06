<?php
    require_once "db_connect.php";

    function phpAlert($msg, $php) {
        echo '<script type="text/javascript">alert("' . $msg . '")</script>';
        echo '<script type="text/javascript">window.location = ("' . $php . '")</script>';
}
    $username = $_POST['username'];

    $sql = "DELETE FROM users WHERE user = ?";
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                phpAlert("Sikeres felhasználó törlés!!", "admins.php");
            }
            else {
                phpAlert("Sikertelen felhasználó törlés!!", "admins.php");
            }
        }
    }
    