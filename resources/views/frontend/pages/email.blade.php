<?php
    if(isset($_POST['submit'])){
	$host = "support@lunazmoto.com";
    $username = "support@lunazmoto.com";
    $password = "bL7MccJoe";
    $port = "587";
	$to = "support@lunazmoto.com"; // this is your Email address
    $from = $_POST['Email']; // this is the sender's Email address
    $first_name = $_POST['Name'];
	$user_email = $_POST['Email'];
	$subject = $_POST['Subject'];
	$Message = $_POST['Message'];
    $subject = "Form Contact Us Enquiry ";   
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers = "From:" . $from;
    mail($to,$subject,$message,$headers);
    header('Location:/contact/send="sucees"'); // sends a copy of the message to the sender
    echo "Mail Sent. Thank you " . $first_name . ", we will contact you shortly.";
    }
?>
