<?PHP
// connect to the database, $Database_connection acts as the link
require('../../mysqli_connection.php');
// allows me to debug the script
ini_set('display errors', 1);
error_reporting(E_ALL | E_STRICT);
// if the user navigates to this page directly, nothing should happen.
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = array();
    if(!empty($_POST['phone_number'])){
        $phone_number = mysqli_real_escape_string($Database_connection, trim($_POST['phone_number']));
    }else{
        $phone_number = null;
        $errors[] = "<p>You must enter you phone number</p>";
    }
    if(!empty($_POST['confirmation_number'])){
        $confirmation_number = mysqli_real_escape_string($Database_connection, trim($_POST['confirmation_number']));
    }else{
        $confirmation_number = null;
        $errors[] = "<p>You must enter the five digit confirmation number sent to your phone</p>";
    }
    // if the form is valid, check if the confirmation number is right. If it is activate the user.
    if(empty($errors)){
        $get_confirmation_number_query = "SELECT confirmation_number FROM users WHERE phone_number = '$phone_number' ";
        $confirmation_number_mysql_format = mysqli_query($Database_connection, $get_confirmation_number_query);
        while($confirmation_number_array = mysqli_fetch_array($confirmation_number_mysql_format, MYSQLI_ASSOC)){
            $confirmation_number_from_db = $confirmation_number_array['confirmation_number'];
        }
        if($confirmation_number == $confirmation_number_from_db){
            $confirm_user_query = "UPDATE users SET active = 1 WHERE phone_number = $phone_number";
            $activate_user = mysqli_query($Database_connection, $confirm_user_query);
            if($activate_user){
                echo "<p>Thank you for confirming your registration, you will now begin receiving text messages.</p>";
            }else{
            echo"<h1>System Error</h1>";
            echo"<p>You could not be confirmed due to a system error.</p>";
            echo'<p>'.mysqli_error($Database_connection).'<br /><br />Query: '.$query.'</p>';
            }
        }else{
            echo "Sorry it doesn't look like that is number that was sent to you ";
        }
    }else{
        foreach($errors as $error){
            echo $error;
        }
    }
}
?>