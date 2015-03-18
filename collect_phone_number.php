<?PHP
// connect to database, '$Database_connection' acts as the link
require('../../mysqli_connection.php');
// allows me to debug the script
ini_set('display errors', 1);
error_reporting(E_ALL | E_STRICT);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $Errors = array();
    if(!empty($_POST['name'])){
        $Name = mysqli_real_escape_string($Database_connection, trim($_POST['name']));
    }else{
        $Errors[] = 'You need to enter a name';
    }
    if(!empty($_POST['phone_number'])){
        $Phone_number = mysqli_real_escape_string($Database_connection, trim($_POST['phone_number']));
    }else{
        $Errors[] = 'you must enter a phone number';
    }
    $Provider_to_domain = array(
           "ATT" => "txt.att.net",
           "Bell_canada" => "txt.bell.ca",
           "Boost_mobile" => "myboostmobile.com",
           "Metro_pcs" => "mymetropcs.com",
           "Sprint" => "messaging.sprintpcs.com",
           "T_mobile" => "tmomail.net",
           "Verizon" => "vtext.com",
           "Virgin_mobile" => "vmobl.com",
           );
    if(!empty($_POST['service_provider'])){
        if(array_key_exists($_POST['service_provider'], $Provider_to_domain)){
            $Provider = mysqli_real_escape_string($Database_connection, trim($_POST['service_provider']));
        }else{
            $Provider = false;
            $Errors[] = 'Currently only the service providers listed are supported.';
        }
    }else{
        $Errors[] = 'you must enter a service provider';
        $Provider = false;
    }
    $Active = 0;
    $Confirmation_number = rand(10000, 99999);

// The phone number should be 10 digit long number, but this acts as a second check
    $Regex_pattern = "/^\d{10}$/";
    If (preg_match($Regex_pattern, $Phone_number) && $Provider){
        $Email_address = $Phone_number.'@'.$Provider_to_domain[$Provider];
    }else{
        $Errors[] = 'You need to enter your ten digit phone number using only numbers';
        $Email_address = null;
    }
    if(!empty($Errors)){
        foreach($Errors as $Error){
            echo "<p>$Error</p>";
        }
        echo"<a href = './'>click here to go back and fix that</a>";
    }else{
        $Query = "INSERT INTO users(email, name, active, confirmation_number) VALUES ('$Email_address', '$Name', '$Active', '$Confirmation_number')";
        $Request = mysqli_query($Database_connection, $Query);
        if($Request){
            $Body_message = "<p>You have successfully registered, look for a text with a confirmation number and enter it on the next page</p>";
            $Redirect_script = "<script src = 'redirect_to_confirmation_page.js'></script>";
            $Pass_phone_number = "<form method = 'POST' id = 'Pass_number' action = 'confirmation.php'><input type = 'hidden' name = 'Phone_number' value = $Phone_number></form>";
            $Return_page = "<!Doctype html>\n<html>\n<head>\n<meta charset = utf8>\n</head>\n<body>\n$Body_message\n$Pass_phone_number\n$Redirect_script\n</body>\n</html>";
            echo $Return_page;
        }else{
            echo"<h1>System Error</h1>";
            echo"<p>You could not be registered due to a system error.</p>";
            echo'<p>'.mysqli_error($Database_connection).'<br /><br />Query: '.$Query.'</p>';
        }
    }
}
?>