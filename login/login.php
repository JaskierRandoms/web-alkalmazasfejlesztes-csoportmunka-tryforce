<?php 
    require_once "db_connect.php";

    function p($name){
        return $_POST[$name];
    }

    function Data($data){
        $data = trim($data);
        $data = strip_tags($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = $password = $alert =  "";
    $username_err = $password_err = "";
    $id = $res_username = $res_password = $res_admin = "";

    function phpAlert($msg, $php) {
            echo '<script type="text/javascript">alert("' . $msg . '")</script>';
            echo '<script type="text/javascript">window.location = ("' . $php . '")</script>';
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty(Data(p("username")))) {
            $username_err = "Felhasználónév megadása kötelező";
        }
        elseif (empty(Data(p("password")))) {
            $password_err = "Jelszó megadása kötelező";
        }
        else {
            $sql = "SELECT * FROM users WHERE user = ? AND password = ? ";

            if ($stmt = mysqli_prepare($con, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

                $param_username = Data(p("username"));
                $param_password = sha1(Data(p("password")));

                if (mysqli_stmt_execute($stmt)) {
                    $stmt->bind_result($res_id, $res_username, $res_password, $res_admin);
                    $stmt->store_result();
                    $result = $stmt->get_result();
                    $stmt->fetch();

                    
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        if ($res_admin == 1) {
                            phpAlert("Sikeres belépés", "admins.php");
                        }
                        else {
                            phpAlert("Sikeres belépés", "users.php");
                        }
                    }
                    else if(mysqli_stmt_num_rows($stmt) == 0){
                        phpAlert("Sikertelen belépés", "admins.php");
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
    }
    



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Bejelentkezés</h2>
        <p>Bejelenzkezéshez töltse ki az űrlapot!</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Felhasználónév:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Jelszó:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Bejelentkezés">
            </div>
            <p>Még nincs profilod? <a href="register.php">Regisztrálj itt!</a>.</p>
        </form>
    </div>    
</body>
</html>