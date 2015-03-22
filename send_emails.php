<?PHP
require('../../mysqli_connection.php');
// allows me to debug the script remove before app goes live
ini_set('display errors', 1);
error_reporting(E_ALL | E_STRICT);
$get_users_query = "SELECT phone_number, domain FROM `users` LEFT JOIN `provider` ON `users`.service_provider = `provider`.provider_id WHERE active = 1;";
$users_mysqli_format = mysqli_query($Database_connection, $get_users_query);
$email_addresses = [];
while($user = mysqli_fetch_array($users_mysqli_format, MYSQLI_ASSOC)){
    $phone_number = $user['phone_number'];
    $domain = $user['domain'];
    $email_addresses[] = $phone_number."@".$domain;
}
$today = date("m/d");
echo $today;
$scientists_birthday_query = "SELECT * FROM `scientists` WHERE birth_date = '$today';";
$get_todays_scientists = mysqli_query($Database_connection, $scientists_birthday_query);
$scientists_born_today = [];
while($scientist = mysqli_fetch_array($get_todays_scientists, MYSQLI_ASSOC)){
    $scientists_born_today[] = $scientist;
    echo $scientist;
}
echo count($scientists_born_today);
if(count($scientists_born_today) > 1 ){
    // if there are more than one scientist with today as a birthday, pick one randomly and send it to everyone
    $selector = rand(0,count($scientists_born_today)-1);
    $scientist = $scientist_born_today[$selector];
}elseif(empty($scientists_born_today)){
    $scientist = null;
}else{
    //if there is only one scientist with today as a birthday use that scientist
    $scientist = $scientists_born_today[0];
}
echo "$scientist";
if($scientist){
    if(!empty($scientist['last_name'])){
        $scientist_name = $scientist['first_name']." ".$scientist['last_name'];
    }else{
        $scientist_name = $scientist['first_name'];
    }
    $contribution = $scientist['contribution'];
    $message = "Happy birthday $scientist_name, today we would like to thank you for $contribution.";
    $subject = "";
    $from = "Scientist-birthday-mailer@nicholasoliverio.com";
    foreach($email_addresses as $email_address){
        mail($email_address, $subject, $message, "From:".$from);
        echo 'sent';
    }
}
?>