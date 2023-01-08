<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB Credentials and connection ->
$hostname = "";
$database = "mindflo";
$username = "";
$password = "";

$conn = new mysqli($hostname,$database, $username, $password);

// Check connection
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

if($_SERVER["REQUEST_METHOD"] == "POST"){
    try{
        $email = $_REQUEST['EMAIL']; //

        if(!empty($email)) {

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

            $sql = "INSERT INTO userlead (ID, Email, CreatedDate,Location) VALUES (NULL, '$email', NULL, '$countryName')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
              } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
              }
              $conn->close();
        }

    }
    catch (Exception $e) {
        //session_start();
            
        //$_SESSION['status'] = 0;
        echo "Message could not be sent. Mailer Error: {$conn}";
    }
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


