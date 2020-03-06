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

    $username = $password = $confirm_password = $alert = "";
    $username_err = $password_err = $confirm_password_err = "";


    function phpAlert($msg, $php) {
        echo '<script type="text/javascript">alert("' . $msg . '")</script>';
        echo '<script type="text/javascript">window.location = ("' . $php . '")</script>';
}

    function Reserved($user, $con){
        $sql = "SELECT user FROM users WHERE user='$user'";
        $result = $con->query($sql);
        $rows = $result->num_rows;
        if ($rows > 0) {
            return true;
        }
        else{
            return false;
        }
    }



    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //felhasználónév validálás
        if (empty(Data(p("username")))) {
            $username_err = "Felhasználónév megadása kötelező!";
        }
        else {
            $sql = "SELECT user FROM users WHERE user = ?;";
            
            if ($stmt = mysqli_prepare($con, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                $param_username = Data(p("username"));
            }
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Sikertelen regisztráció: Foglalt felhasználónév!";
                }
                else {
                    $username = Data(p("username"));
                }
            }
            else {
                echo "Probálja meg később";
            }
            mysqli_stmt_close($stmt);
        }

        //jelszó validálás
        if (empty(Data(p("password")))) {
            $password_err = "Jelszó megadása kötelező!";
        }
        elseif (strlen(Data(p("password"))) < 6) {
            $password_err = "A jelszónak legalább 6 karakter hosszúnak kell lennie. Kérjük, válassz másikat.";
        }
        else {
            $password = Data(p("password"));
        }

        //jelszó megerősítő validálás
        if (empty(Data(p("confirm_password")))) {
            $confirm_password_err = "Megerősítő jelszó megadása kötelező!";
        }
        elseif (empty($password_err) && $password != Data(p("confirm_password"))) {
            $confirm_password_err = "A jelszavak nem egyeznek!";
        }
        else {
            $confirm_password = Data(p("confirm_password"));
        }


        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = "INSERT INTO users (user, password) VALUES (?, ?)";
            if ($stmt = mysqli_prepare($con, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = sha1($password);

                if (mysqli_stmt_execute($stmt)) {
                    header("location: login.php");
                }
                else {    
                    header("location: register.php");
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
    <title>Regisztráció</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Regisztráció</h2>
        <p>Regisztrációhoz töltse ki az űrlapot!</p>
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
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Megerősítő jelszó:</label></label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Regisztráció">
            </div>
            <p>Már van profilod? <a href="login.php">Jelentkezbe itt!</a>.</p>
        </form>
    </div>    
</body>
</html>