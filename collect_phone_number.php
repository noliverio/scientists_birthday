<?PHP
$Name = $_POST['Name'];
$Phone_number = $_POST['Phone_number'];
$Provider = $_POST['Service_provider'];
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
//  The phone number should be 10 digits long, but this acts as a second check
If (strlen($Phone_number) === 10){
    $Email_address = $Phone_number.'@'.$Provider_to_domain[$Provider];
    echo "$Email_address";
}else{
    echo "<p> oops, it looks like either your phone number is incorrect or you got here without going through the sign-up page.</p>";
    echo "<p><a href = /scientists_birthdays/> If you want to sign up try entering your phone number again</a></p>";
}
?>