<?PHP
// connect to database, '$Database_connection' acts as the link
require('../../mysqli_connection.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = array();
    if(!empty($_POST['name'])){
        $name = mysqli_real_escape_string($Database_connection, trim($_POST['name']));
    }else{
        $errors[] = 'You need to enter a name';
    }
    if(!empty($_POST['phone_number'])){
        $phone_number = mysqli_real_escape_string($Database_connection, trim($_POST['phone_number']));
    }else{
        $errors[] = 'you must enter a phone number';
    }
    $provider_to_domain = array(
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
        if(array_key_exists($_POST['service_provider'], $provider_to_domain)){
            $provider = mysqli_real_escape_string($Database_connection, trim($_POST['service_provider']));
        }else{
            $provider = false;
            $errors[] = 'Currently only the service providers listed are supported.';
        }
    }else{
        $errors[] = 'you must enter a service provider';
        $provider = false;
    }
    $active = 0;
    $confirmation_number = rand(10000, 99999);

// The phone number should be 10 digit long number, but this acts as a second check
    $regex_pattern = "/^\d{10}$/";
    If (preg_match($regex_pattern, $phone_number) && $provider){
        $email_address = $phone_number.'@'.$provider_to_domain[$provider];
    }else{
        $errors[] = 'You need to enter your ten digit phone number using only numbers';
        $email_address = null;
    }
    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p>$error</p>";
        }
        echo"<a href = './'>click here to go back and fix that</a>";
    }else{
        $query = "INSERT INTO users(email, name, active, confirmation_number) VALUES ('$email_address', '$name', '$active', '$confirmation_number')";
        $request = mysqli_query($Database_connection, $query);
        if($request){
            $body_message = "<p>You have successfully registered, look for a text with a confirmation number and enter it on the next page</p>";
            $redirect_script = "<script src = 'redirect_to_confirmation_page.js'></script>";
            $pass_phone_number = "<form method = 'POST' id = 'Pass_number' action = 'confirmation.php'><input type = 'hidden' name = 'Phone_number' value = $phone_number></form>";
            $return_page = "<!Doctype html>\n<html>\n<head>\n<meta charset = utf8>\n</head>\n<body>\n$body_message\n$pass_phone_number\n$redirect_script\n</body>\n</html>";
            echo $return_page;
        }else{
            echo"<p>Sorry there was a problem registering you, please try again later.</p>";
        }
    }
}
?>