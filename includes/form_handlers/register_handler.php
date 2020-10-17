<?php
//Declarig variables to prevent errors
//$name = ""; //first name
$username = ""; //username
$email = ""; //email
$pass = ""; //pass
//$gender = ""; //gender
//$profile_pic = ""; //profile pic
$error_array = array();//holds error messages

if(isset($_POST['sign_btn'])) {

  //Registration form values

  //Name
  //$name = htmlspecialchars($_POST['sign_name']); //remove html tags
  //$fname = str_replace(' ', '', $fname); //remove spaces
  //$name = ucwords($name); //uppercase first letter of everyword
  //$_SESSION['sign_name']=$name;//Stores first name into session variables

  //Username
  $username = htmlspecialchars($_POST['sign_username']); //remove html tags
  //$fname = str_replace(' ', '', $fname); //remove spaces
  // $username = ucfirst(strtolower($username)); //uppercase first letter
  $_SESSION['sign_username']=$username;//Stores user name into session variables

  //Email
  $email = strip_tags($_POST['sign_email']); //remove html tags
  //$email = str_replace(' ', '', $email); //remove spaces
  $email = strtolower($email); //uppercase first letter
  $_SESSION['sign_email']=$email;//Stores email into session variables

  //pass
  $pass = $_POST['sign_password']; //remove html tags
  //$pass = str_replace(' ', '', $pass); //remove spaces

  //Gender
  //$gender = htmlspecialchars($_POST['reg_gender']);

  //Date
  $date = date("Y-m-d H:i:s");

  //Generate VKEY
  $vkey=md5(time().$username); 

  //Check what gender user is 
  //$g_check = mysqli_query($con,"SELECT gender from users WHERE gender='$gender'");

  //and assign profile_pic
  //if($gender == "Male"){
  //   $profile_pic = "/studentsasylum/assets/images/svg/male.svg";
  // }
  //elseif($gender == "Female"){
  //   $profile_pic = "/studentsasylum/assets/images/svg/female.svg";
  // }
  // else{
  //   $profile_pic = "/studentsasylum/assets/images/svg/transgender.png";
  // }

  $profile_pic = "/studentsasylum/assets/images/svg/default_profile_pic.svg";
  
  //Check if email already exists
  $e_check=mysqli_query($con,"SELECT email FROM users WHERE email='$email'");
  
  //Count no of rows returned
  $num_rows = mysqli_num_rows($e_check);
  if($num_rows>0){
    array_push($error_array, "Email already in use <br>");
    echo "Email already in use <br>";
  }
  
  //Check if username already exists
  $username_check=mysqli_query($con,"SELECT email FROM users WHERE email='$email'");

  //Count no of rows returned
  $num_rows = mysqli_num_rows($username_check);
  if($num_rows>0){
    array_push($error_array, "Username already in use <br>");
    echo "Username already in use <br>";
  }
  
  if(strlen($username)>25 || strlen($username)<3){
    array_push($error_array,"Your username must be between 3 and 25 characters <br>");
    echo "Your username must be between 3 and 25 characters <br>";
  }
  if(!preg_match("/\d/", $pass)){
    array_push($error_array, "Your password cannot contain any special chatacters <br>");
    echo "Your password must contain at least one digit <br>";
  }
  if(!preg_match("/[A-Z]/", $pass)){
    array_push($error_array, "Your password cannot contain any special chatacters <br>");
    echo "Your password must contain at least one Capital Letter <br>";
  }
  if(!preg_match("/[a-z]/", $pass)){
    array_push($error_array, "Your password cannot contain any special chatacters <br>");
    echo "Your password must contain at least one small Letter <br>";
  }
  if(!preg_match("/\W/", $pass)){
    array_push($error_array, "Your password cannot contain any special chatacters <br>");
    echo "Your password must contain at least one special character<br>";
  }
  if(preg_match("/\s/", $pass)){
    array_push($error_array, "Your password cannot contain any special chatacters <br>");
    echo "Your password must not contain any space<br>";
  }
  if(strlen($pass)>30 || strlen($pass)<8){
    array_push($error_array, "Your password must be between 8 and 30 characters <br>");
    echo "Your password must be between 8 and 30 characters <br>";
  }

  //Check username already exists or not
  $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
  if(mysqli_num_rows($check_username_query)!=0){
    array_push($error_array, "Username already in use. Please try another Username");
    echo "Username already in use. Please try another Username<br>";
    $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
  }

  if ($username == trim($username) && strpos($username, ' ') !== false){
    array_push($error_array, "No Spaces are allowed");
    echo "No Spaces are allowed in username<br>";
  }

  if(empty($error_array)){

    $pass = password_hash($pass, PASSWORD_DEFAULT);  //Encrypt password before sending to database

    $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$username', '$email', '$pass', '$profile_pic', '$date', '$vkey', '', '0', '0', '0', 'no', ',$username,')");

    array_push($error_array,"You are ready now!!! Login into your account!!!<br>");

    if($query){
      //Send Email
      $to = $email;
      $subject = "Email Verification";
      $message = "<a href='http://localhost/studentsasylum/verification/verify.php?vkey=$vkey'>Register Account</a>";
      $headers = "From: srinikhilreddy.g@gmail.com \r\n";
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      
      mail($to,$subject,$message,$headers);
    }

    //Clear session variables 
    $_SESSION['sign_username'] = "";
    $_SESSION['sign_email'] = "";
    $_SESSION['sign_pass'] = "";
    header("location: emailverification.html");
  }
	
}

if(isset($_POST['log_btn'])) {
 
	$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //sanitize email
 
  $_SESSION['log_email'] = $email; //Store email into session variable 
  // $username = htmlspecialchars($_POST['log_username']);
	$pass = $_POST['log_password']; //Get password

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
	$check_login_query = mysqli_num_rows($check_database_query);
 
	if($check_login_query == 1) {
		while($row = mysqli_fetch_array($check_database_query)){
			if(password_verify($pass, $row['pass'])){
        $username = $row['username'];
        setcookie('emailbun', $email, time()+756864000);
        setcookie('passbun', $pass, time()+756864000);
 
				$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
				if(mysqli_num_rows($user_closed_query) == 1) {
					$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
				}
		
				$_SESSION['username'] = $username;
				echo "Username: " .$username;
				header("Location: index.php");
				exit();
			}
		}
		
	}
	else {
		echo "Email or password was incorrect<br>";
	}
 
}
 
?>