<?PHP
// connect to database, '$Database_connection' acts as the link
require('../../mysqli_connection.php');
// allows me to debug the script
ini_set('display errors', 1);
error_reporting(E_ALL | E_STRICT);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = array();
    if(!empty($_POST['name'])){
        $name = mysqli_real_escape_string($Database_connection, trim($_POST['name']));
    }else{
        $errors[] = 'You need to enter a name';
    }
// To get through the js the phone number should be 10 digit long number, but this acts as a second check
    if(!empty($_POST['phone_number'])){
        $regex_pattern = '/^\d{10}$/';
        if(preg_match($regex_pattern, mysqli_real_escape_string($Database_connection, trim($_POST['phone_number'])))){
            $phone_number = mysqli_real_escape_string($Database_connection, trim($_POST['phone_number']));
        }else{
            $errors[] = 'You need to enter your ten digit phone number using only numbers';
            $phone_number = null;
        }
    }else{
        $errors[] = 'you must enter a phone number';
    }

    if(!empty($_POST['service_provider'])){
        $provider = mysqli_real_escape_string($Database_connection, trim($_POST['service_provider']));
    }else{
        $errors[] = 'you must enter a service provider';
        $provider = null;
    }

    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p>$error</p>";
        }
        echo"<a href = './'>click here to go back and fix that</a>";
    }else{
        $active = 0;
        $confirmation_number = rand(10000, 99999);
        $query = "INSERT INTO users(phone_number, name, active, confirmation_number, service_provider) VALUES ('$phone_number', '$name', '$active', '$confirmation_number', (SELECT provider_id FROM provider WHERE name = '$provider'));";
        $request = mysqli_query($Database_connection, $query);
        if($request){
            send_user_confirmation_number($Database_connection, $phone_number, $provider, $confirmation_number);
            $body_message = "<p>You have successfully registered, look for a text with a confirmation number and enter it on the next page</p>";
            $redirect_script = "<script src = 'redirect_to_confirmation_page.js'></script>";
            $pass_phone_number = "<form method = 'POST' id = 'Pass_number' action = 'confirmation.php'><input type = 'hidden' name = 'Phone_number' value = $phone_number></form>";
            $return_page = "<!Doctype html>\n<html>\n<head>\n<meta charset = utf8>\n</head>\n<body>\n$body_message\n$pass_phone_number\n$redirect_script\n</body>\n</html>";
            echo $return_page;
            
        }else{
            echo 'There was an error registering you, please try again later';
        }
    }
}
function send_user_confirmation_number($Database_connection, $number, $service_provider, $confirmation){
    $get_domain_query = "SELECT domain FROM provider WHERE name = '$service_provider'";
    $domain_mysql_format = mysqli_query($Database_connection, $get_domain_query);
    while($domain_array = mysqli_fetch_array($domain_mysql_format, MYSQLI_ASSOC)) {
        $domain = $domain_array['domain'];
    }
    $email_address = $number."@".$domain;
    $message = "Your verification code is $confirmation";
    $subject = "";
    $from = "Scientist-birthday-mailer@nicholasoliverio.com";
    mail($email_address, $subject, $message, "From:".$from);
}
mysqli_close($Database_connection);
?>