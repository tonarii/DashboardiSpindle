<?PHP

$inputJSON = file_get_contents('php://input');

$data = json_decode($inputJSON);

$date = date("Y-m-d H:i:s");

$servername = "servername";
$dbname = "dbname";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$sql = 'INSERT INTO Data (Timestamp, Name, ID, Angle, Temperature, Battery, ResetFlag, Gravity, UserToken, `Interval`, RSSI)
VALUES ("'.$date.'", "'.$data->name.'", "'.$data->ID.'", "'.$data->angle.'", "'.$data->temperature.'", "'.$data->battery.'", "'.$data->ResetFlag.'", "'.$data->gravity.'", "'.$data->token.'", "'.$data->interval.'", "'.$data->RSSI.'")';

if ($conn->query($sql) === TRUE) {
echo "New record created successfully";
}else {
echo "Error: " . $sql . "
" . $conn->error;
}

$conn->close();
?>
