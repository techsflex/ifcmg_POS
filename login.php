<?php
/* User login process, checks if user exists and password is correct */

// Escape username to protect against SQL injections
$username = $conn->escape_string($_POST['username']);
$result = $conn->query("SELECT * FROM login WHERE username='$username'");


if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "Username does not exist!";
	$conn->close();
    header("location: error.php");
}
else { // User exists
	
    $login = $result->fetch_assoc();

    if ( password_verify($_POST['password'], $login['password']) ) {
        $users = $conn->query("SELECT * FROM users WHERE login_username='$username'"); //Search table `users` for valid username
		$user = $users->fetch_assoc(); // Fetch associated values against hit
		
        $_SESSION['status'] = $user['status_statusID'];
        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;
        
        if ( $_SESSION['status'] === '1') {
            $_SESSION['logged_in'] = false;
            $_SESSION['message'] = 'Your account is currently unverified, please contact administrator';
			$conn->close();
            header("location: error.php");
        }

        elseif ( $_SESSION['status'] === '2') {
            $_SESSION['logged_in'] = false;
            $_SESSION['message'] = 'Your account has temporarily been blocked, please contact administrator';
			$conn->close();
            header("location: error.php");
        }

        elseif ( $_SESSION['status'] === '3') {
            $_SESSION['logged_in'] = false;
            $_SESSION['message'] = 'The account you mentioned has been closed, please apply for a new registration';
			$conn->close();
            header("location: error.php");
        }

        elseif ( $_SESSION['status'] === '4' || $_SESSION['status'] === '5' || $_SESSION['status'] === '6') {
        	$_SESSION['first_name'] = $user['first_name'];
        	$_SESSION['last_name'] = $user['last_name'];
			$_SESSION['companyID'] = $user['company_companyID'];
            $_SESSION['statusID'] = $user['status_statusID'];
			$conn->close();
            header("location: home.php");
        }        
        
        else {
            $_SESSION['logged_in'] = false;
            $_SESSION['message'] = 'Invalid status has been defined against this username, please contact administrator';
			$conn->close();
            header("location: error.php");
        }
    }

    else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
		$conn->close();
        header("location: error.php");
    }
}


?>