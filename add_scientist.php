<?PHP
if($_SERVER['REQUEST_METHOD'] == 'POST'{
    $errors = array();
}
// check if each of the following required fields are filled out and add an error for any missing
if(!empty($_POST['first_name'])){
    $First_name = trim($_POST['first_name']);
}else{
    $errors[] = 'You must enter a first name, if the scientist only is known by one name (ex Hypatia) then enter it as a first name.'; 
}
if(!empty($_POST['last_name'])){
    $Last_name = trim($_POST['last_name']);
}else{
    $Last_name = null;
}
if(!empty($_POST['contribution'];)){
    $Contribution = trim($_POST['contribution']);
}else{
    $error[] = "You must provide the scientist's contribution to our body of knowledge.";
}
if(!empty($_POST['birthday'];)){
    $Birth_date_unchecked = trim($_POST['birthday']);
    // establish that the birthday is given in mm/dd form using the regular expression ^\d{2}\/\d{2}$
    $Regex_pattern = "/^\d{2}\/\d{2}$/";
    if(preg_match($Regex_pattern, $Birth_date_unchecked)){
        $Birth_date = $Birth_date_unchecked;
    }else{
        error[] = "You must provide the scientist's birthday in mm/dd form.";
    }
}else{
    $error[] = "You must provide the scientist's birthday.";
}

// max message length = 134 chars, subject is not needed
// scientist + contribution can be up to 78 chars in order to safely send the sms
$Message = "Happy birthday $Scientist, today we would like to thank you for $Contribution.";
// The javascript on the form should mostly enforce this, however because script runs
// onkeyup it is very easy to get past the character requirement. This will act as a
// as a more reliable check 
if (strlen($Message) <= 134){
    echo "<p>$Message</p>";
}else{
    $error[] "<p>Sorry the message would exceed the maximum length. </p><a href = /scientists_birthdays/add_scientist_form.html> Go back and try to write a new contribution message</a>";
};
// of there are any errors provide the user with the error messages.
// otherwise if there are no errors save the information to the database.
if(empty($errors)
?>