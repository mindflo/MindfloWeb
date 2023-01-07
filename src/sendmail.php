<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
	
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output  //SMTP::DEBUG_SERVER
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.ipage.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'connect@mindflo.in';           //change here          //SMTP username
    $mail->Password   = '5Ywz1MuEWCCnyqB8';       //change here          //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;     //Enable implicit TLS encryption //PHPMailer::ENCRYPTION_SMTPS  //PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 465;                                //465   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
    //if(isset($POST['submit'])){
        $to = "connect@mindflo.in"; // this is your Email address
        $from = $_REQUEST['EMAIL']; // this is the sender's Email address
        //$phone = $_REQUEST['phone'];
        //$first_name = $_REQUEST['firstName'];
        //$last_name = $_REQUEST['lastName'];
        //$message = $_REQUEST['message'];
        $subject = "Mindflo Email Subscription";
		$ipAddress = get_client_ip();
		// Use JSON encoded string and converts
		// it into a PHP variable
		$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ipAddress));
		
		$countryName = $ipdat->geoplugin_countryName;
		$cityName= $ipdat->geoplugin_city;
		$continentName= $ipdat->geoplugin_continentName;
		$latitude= $ipdat->geoplugin_latitude;
		$longitude= $ipdat->geoplugin_longitude;
		$currencySymbol= $ipdat->geoplugin_currencySymbol;
		$currencyCode= $ipdat->geoplugin_currencyCode;
		$timezone= $ipdat->geoplugin_timezone;
		
        //Recipients
        $mail->setFrom('contact@mindflo.in', "Mindflo");
        $mail->addAddress('mindfloinnovations@gmail.com', "Business");     //Add a recipient
        $mail->addAddress($to, 'Mindflo | Innovations'); //Name is optional
		//$mail->addAddress('musique.suman@gmail.com', 'SA Global'); 
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('musique.suman@gmail.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
       // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

		$adminTemplate = file_get_contents("inc/admin-template.php");
		//Replace template value
		$body = str_replace("{{customerEmail}}", $from, $adminTemplate);
		//$body = str_replace("{{customerName}}", $first_name.' '.$last_name, $body);
		//$body = str_replace("{{customerPhone}}", $phone, $body);
		//$body = str_replace("{{customerQuery}}", $message, $body);
		$body = str_replace("{{customerIP}}", $ipAddress, $body);
		
		$body = str_replace("{{countryName}}", $countryName, $body);
		$body = str_replace("{{cityName}}", $cityName, $body);
		$body = str_replace("{{continentName}}", $continentName, $body);
		$body = str_replace("{{latitude}}", $latitude, $body);
		$body = str_replace("{{longitude}}", $longitude, $body);
		$body = str_replace("{{currencySymbol}}", $currencySymbol, $body);
		$body = str_replace("{{currencyCode}}", $currencyCode, $body);
		$body = str_replace("{{timezone}}", $timezone, $body);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = 'IP Address: ' .$ipAddress;

        $mail->send();
         echo 'Message has been sent';
		
		//session_start();
		
		//$_SESSION['status'] = 1;
		
		
		//header('Location: index.php');
		//exit;

		//echo 'something.'.$_REQUEST['email'];
        //send the message, check for errors
        if (!$mail->send()) { 
            $result = array('status'=>"error", 'message'=>"Mailer Error: ".$mail->ErrorInfo);//
            echo json_encode($result);
			//echo $_REQUEST['email'];
        } else {
            $result = array('status'=>"success", 'message'=>"Message sent.");
            echo json_encode($result);
			//echo 'error';
        }
    //}

    
} catch (Exception $e) {
	//session_start();
		
	//$_SESSION['status'] = 0;
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>

