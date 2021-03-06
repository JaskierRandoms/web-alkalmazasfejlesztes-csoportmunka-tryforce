<?php 
    require_once "db_connect.php";
    
    function phpAlert($msg, $php) {
        echo '<script type="text/javascript">alert("' . $msg . '")</script>';
        echo '<script type="text/javascript">window.location = ("' . $php . '")</script>';
}

    function Show($con){
        $sql = "SELECT user, admin FROM users";
        
        $resultrows = 0;
        $output = "";

        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $result = $con->query($sql);
            $resultrows = mysqli_stmt_num_rows($stmt);

            if ($resultrows > 0) {
                $i = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $output .= "<tr";
                    $output .= ($i%2==0) ? "" : " class = 'paros'";
                    $output .= ">";
                        $output .= "<td>" . $row['user'] . "</td>";
                        $output .= "<td>";
                        if ($row['admin'] == 1) {
                            $output .= "Admin";
                        }
                        elseif ($row['admin'] == 0) {
                            $output .= "Felhasználó";
                        }
                        $output .= "</td>";
                        $output .= "<td>";
                            $output .= "<form action='delete.php' method='POST'>";
                                $output .= "<input type='hidden' name='username' value='";
                                $output .= $row['user'] . "' />";
                                $output .= "<input type='submit' value='Törlés' />";
                            $output .= "</form>";
                        $output .= "</td>";
                        $output .= "<td>";
                            $output .= "<form action='authority.php' method='POST'>";
                                $output .= "<input type='hidden' name='username' value='";
                                $output .= $row['user'] . "' />";
                                $output .= "<input type='submit' name='status' value='";
                                if ($row['admin'] == 1) {
                                    $output .= "Felhasználó";
                                }
                                elseif ($row['admin'] == 0) {
                                    $output .= "Admin";
                                }
                                $output .= "'/>";
                            $output .= "</form>";
                        $output .= "</td>";
                    $output .= "</tr>";
                    $i++;
                }	
            }
        }
        else {
            $output = "Probálja meg később";
        }
        return $output;
        mysqli_close($con);
    }
?>

<!DOCTYPE html>
<html>
<head>
	<style>
	.paros {
			background-color: #ddd;
		}
		
	.cim {
			background-color: #aaa;
		}
	h2{
		margin-left: 10%;
	}
</style>
	<title>Felhasználók</title>
</head>
<body>
	<h2>Felhasználók</h2>
	<table>
	<thead>
		<tr>
			<th class="cim">Felhasználónév</th>
			<th class="cim">Jogosultság</th>
			<th class="cim">Törlés</th>
			<th class="cim">Jogosultság</th>
		</tr>
	</thead>
	<tbody>
		<?php echo Show($con); ?>
	</tbody>	
</table>
<br/><br/>
<form action="register.php">
	<input type="submit" value="Regisztráció" />
</form>
<br/>
<form action="login.php">
	<input type="submit" value="Bejelentkezés" />
</form>
</body>
</html>