<?php
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */

// Set session variables to be used on profile.php page
$_SESSION['username'] = $_POST['username'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name'] = $_POST['lastname'];

// Escape all $_POST variables to protect against SQL injections
$first_name = $conn->escape_string($_POST['firstname']);
$last_name = $conn->escape_string($_POST['lastname']);
$username = $conn->escape_string($_POST['username']);
$password = $conn->escape_string(password_hash($_POST['password'], PASSWORD_DEFAULT));
      
// Check if user with that email already exists
$result = $conn->query("SELECT * FROM login WHERE username='$username'") or die($mysqli->error());

// We know user email exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
    
    $_SESSION['message'] = 'User with this username already exists!';
	$conn->close;
    header("location: error.php");
    
}
else { // Email doesn't already exist in a database, proceed...

    $sql_login = "INSERT INTO login (username, password) " 
            . "VALUES ('$username','$password')";
	$sql_users = "INSERT INTO users (first_name, last_name, login_username, status_statusID) " 
            . "VALUES ('$first_name','$last_name', '$username', 1)";

    // Add user to the database
    if ( $conn->query($sql_login) ){
        
		if ( $conn->query($sql_users) ) {
			$_SESSION['message'] = "Confirmation message has been sent to administrator, please wait while
                 we work on verifying your account details!";
			$conn->close;
			header("location: success.php");
		}
		else {
			$_SESSION['message'] = nl2br("Registration failed due to user creation failure!\n\n" . $conn->error);
			$sql_clean = "DELETE FROM login WHERE login.username = '$username'";
			$conn->query($sql_clean);
			$conn->close;
			header("location: error.php");
		}
		 

    }

    else {
        $_SESSION['message'] = 'Registration failed due to login creation failure!'. $conn->error;
		$conn->close;
        header("location: error.php");
    }

}