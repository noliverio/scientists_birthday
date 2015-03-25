This application is written in php and interfaces with a mysql database. 

On a high level view this project sends users a text message with the name of a scientist and a very brief description of thier contributions.

Mysqli_connection.php which is refrenced by several of the scripts, and send_emails.php are placed above the public_html folder for security reasons. send_emails.php is called by a cron job once per day. There has not yet been any css applied so while the registration pages look simple and unapealing it is functional.
