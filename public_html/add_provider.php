<?PHP
require('../../mysqli_connection.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = array();
    if(!empty($_POST['provider'])){
        $provider = mysqli_real_escape_string($Database_connection, trim($_POST['provider']));
    }else{
        $errors[] = "<p>Provider is a required field</p>";
        $provider = null;
    }
    if(!empty($_POST['domain'])){
        $domain = mysqli_real_escape_string($Database_connection, trim($_POST['domain']));
    }else{
        $errors[] = "<p>Domain is a required field</p>";
        $domain = null;
    }
    if(empty($errors)){
        $query = "INSERT INTO provider(name, domain) VALUES ('$provider', '$domain');";
        $request = mysqli_query($Database_connection, $query);
        if(request){
            echo "<p>$provider successfully added!</p>";
            echo "<p><a href = ./add_provider.html>Add another?</a></p>";
        }else{
            echo"<p>Sorry there was a problem registering you, please try again later.</p>";
        }
    }else{
        foreach($errors as $error){
            echo $error;
        }
        echo "<p><a href = ./add_provider.html>Click here to go back and correct the errors</a></p>";
    }
}
?>