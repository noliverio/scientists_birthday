<?PHP
// connect to the database, $Database_connection acts as the link
require('../../mysqli_connection.php');
// allows me to debug the script
ini_set('display errors', 1);
error_reporting(E_ALL | E_STRICT);
// if the user navigates to this page directly, nothing should happen.
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = array();
    if(!empty($_POST['Phone_number'])){
        $phone_number = mysqli_real_escape_string($Database_connection, trim($_POST['Phone_number']));
    }else{
        $phone_number = null;
        $errors[] = "<p>You must enter you phone number</p>";
    }
    if(!empty($_POST['confirmation_number'])){
        $confirmation_number = mysqli_real_escape_string($Database_connection, trim($_POST['confirmation_number']));
    }else{
        $confirmation_number = null;
        $errors[] = "<p>You must enter the five digit confirmation number sent to your phone</p>",
    }
    if(empty($errors)){
        $get_confirmation_number_query = "";
        $confirmation_number_from_db = mysqli_query($Database_connection, $query);
        if($confirmation_number
        
    }else{
        foreach($errors as $error){
            echo $error;
        }
}
?>