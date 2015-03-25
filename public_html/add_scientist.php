<?PHP
// connect to the mysql database, "$Database_connection" acts as the link
require('../../mysqli_connection.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = array();

    // check if each of the following required fields are filled out and add an error for any missing
    if(!empty($_POST['first_name'])){
        $first_name = mysqli_real_escape_string($Database_connection, trim($_POST['first_name']));
        $scientist = $first_name;
    }else{
        $errors[] = '<p>You must enter a first name, if the scientist only is known by one name (ex Hypatia) then enter it as a first name.</p>';
        $scientist = '';
    }
    if(!empty($_POST['last_name'])){
        $last_name = mysqli_real_escape_string($Database_connection, trim($_POST['last_name']));
        $scientist += " ".$last_name;
    }else{
        $last_name = null;
    }
    if(!empty($_POST['contribution'])){
        $contribution = mysqli_real_escape_string($Database_connection, trim($_POST['contribution']));
    }else{
        $errors[] = "<p>You must provide the scientist's contribution to our body of knowledge.</p>";
        $contribution = '';
    }
    if(!empty($_POST['birthday'])){
        $Birth_date_unchecked = trim($_POST['birthday']);
        // establish that the birthday is given in mm/dd form using the regular expression ^\d{2}\/\d{2}$
        $Regex_pattern = "/^\d{2}\/\d{2}$/";
        if(preg_match($Regex_pattern, $Birth_date_unchecked)){
            $Birth_date = $Birth_date_unchecked;
        }else{
            $errors[] = "<p>You must provide the scientist's birthday in mm/dd form.</p>";
        }
    }else{
        $errors[] = "<p>You must provide the scientist's birthday.</p>";
    }

    // max message length = 134 chars, subject is not needed
    // scientist + contribution can be up to 78 chars in order to safely send the sms
    $message = "Happy birthday $scientist, today we would like to thank you for $contribution.";
    // The javascript on the form should mostly enforce this, however because script runs
    // onkeyup it is very easy to get past the character requirement. This will act as a
    // as a more reliable check 
    if (strlen($message) >= 134){
        $error[] = "<p>Sorry the message would exceed the maximum length. </p><a href = /scientists_birthdays/add_scientist_form.html> Go back and try to write a new contribution message</a>";
    }
    // if there are any errors provide the user with the error messages.
    // otherwise if there are no errors save the information to the database.
    if(!empty($errors)){
        foreach($errors as $error){
            echo "$error";
        }
        echo"<a href = './add_scientist_form.html'>click here to go back and fix that</a>";
    }else{
        $query = "INSERT INTO scientists(first_name, last_name, contribution, birth_date) VALUES ('$first_name', '$last_name', '$contribution', '$Birth_date')";
        $request = @mysqli_query($Database_connection, $query);
        if($request){
            echo "$first_name $last_name added to the database";
            echo "<p><a href = './add_scientist_form.html'>Add another</a></p>";
        }else{
            echo"Sorry there was a problem adding $first_name $last_name to the database.";
        }
    mysqli_close($Database_connection);
    }
}
?>