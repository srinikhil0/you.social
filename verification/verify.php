<?php

if(isset($_GET['vkey'])){
    //Process Validation
    $vkey = $_GET['vkey'];

    $con = mysqli_connect("localhost", "root", "", "students_asylum"); //connection variable

    $result = mysqli_query($con, "SELECT verified,vkey FROM users WHERE verified = 0 AND vkey='$vkey' LIMIT 1");

    $rowCount = mysqli_num_rows($result);

    if($rowCount == 1){
        //Validate email
        $update = mysqli_query($con, "UPDATE users SET verified = 1 WHERE vkey='$vkey' LIMIT 1");

        if($update){
            echo "Your account has been verified, you may now login";
            header("location: ../register.php");
        }
        else{
            echo mysqli_error("Error");
        }
    }
    else{
        echo "This account was already verified";
    }
}
else{
    die("Something went wrong");
}

?>

<html>
<head>
    <title>Verified</title>
</head>
<body>
    
</body>
</html>