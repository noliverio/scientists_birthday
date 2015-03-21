<!Doctype html>
<html>
<head>
    <title> confirm your phone number</title>
</head>
<body>
    <form method = "POST" action = "confirm_number.php">
        <p>Please enter the five digit number sent to your phone <input type = "text" name = "confirmation_number" maxlength = "5" size = "5"></p>
        <p>Please enter your phone number <input type = "text" name = "phone_number" maxlength = "10" length = "10" value = "<?PHP $number = $_POST['Phone_number'];echo $number;?>"/></p>
        <input type = "submit" name = "submit" value = "submit">
    </form>
</body>
</html>