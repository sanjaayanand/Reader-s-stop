<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "connection.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            echo "<script>alert('Wrong password');</script>";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                   echo "<script>alert('user doesn't exist ');</script>";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="styling/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="styling/bootstrap-theme.min.css">
	<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
	<script type="text/javascript" src="styling/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<style>
*{
	padding: 0;
	margin: 0;
}
body{
	background: #ad5389;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #3c1053, #ad5389);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #3c1053, #ad5389); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: black;
}

li {
    float:left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 15px 17px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: grey;
}

.active {
    background-color: grey;
}
	
	.form-container{
		padding: 20px 20px 20px 20px;
		background-color: rgba(0,0,0,0.5);
		color: white;
		margin-top: 30%;
		position: relative;
		font-family: 'Roboto', sans-serif;
		font-family: 'Montserrat', sans-serif;
		font-size: 16px;
	}
</style>
<link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto" rel="stylesheet">
</head>
<body>
	<ul>
  <li><a href="index.html" class="active">home</a><li>
</ul>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
							<form class="form-container"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<h2 align="center" style="font-size: 30px">Login</h2>
					<p>Please fill in your credentials to login.</p>
			
			<div class="form-group">
			    <label for="Email">user name</label>
			    <input type="text" name="username" class="form-control" required="">
			</div>
			
			<div class="form-group">
			    <label for="password">password</label>
			    <input type="password" name="password" class="form-control" required="">
                
			</div>
			<div class="checkbox">
				<label style="font-size: 14px">
				<input type="checkbox">Remember me
				</label>
			</div>
			<p align="center" style="font-size: 14px color:green;">I am a new user<a href="signup.php">Register.</a></p>
				<button type="submit" name="s" class="btn btn-success" style="width: 100%">Submit</button>
				</form>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
		
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12"></div>
		</div>
	</div>
</body>
</html>