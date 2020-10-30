<?php

$msg = "";

$con = mysqli_connect("localhost", "root", "", "students_asylum"); //connection variable

if(isset($_POST['recover'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    $check_login_query = mysqli_num_rows($check_database_query);

    if($check_login_query){
        $row = mysqli_fetch_array($check_database_query);
        $name = $row['name'];
        $vkey = $row['vkey'];
        $to = $email;
        $subject = "Password Reset";
        $message = "Hi, $name Click here to reset the password. <a href='http://localhost/studentsasylum/verification/reset_password.php?vkey=$vkey'>Click Here</a>";
        $headers = "From: srinikhilreddy.g@gmail.com \r\n";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        if(mail($to,$subject,$message,$headers)){
            header('location: ../register.php');
        }
        else{
            echo "EMail Sending Failed";
        }
    }
    else{
        echo "No Email Found";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password</title>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        
    </head>
<body>
<div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Forgot Password?</h2>
                  <p>You can reset your password here.</p>
                  <div class="panel-body">
    
                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
    
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                          <input id="email" name="email" placeholder="Email" class="form-control"  type="email">
                        </div>
                      </div>
                      <div class="form-group">
                        <input name="recover" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                      </div>
                      
                      <input type="hidden" class="hide" name="token" id="token" value=""> 
                    </form>
    
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>
</body>
</html>