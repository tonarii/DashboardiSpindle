<?php
// export csv  file
// Create connection
$servername = "SERVER";
$dbname = "DB_NAME";
$username = "USER";
$password = "PASSWORD";
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT Name FROM Data";
$result = $conn->query($sql);
$row=mysqli_fetch_assoc($result);
$name = $row['Name'];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $name = $row['Name'];
    }
}
$filename = "$name/" . date('Y-m-d') . ".csv";
//get records from database
$query = $conn->query("SELECT * FROM Data ORDER BY Timestamp DESC");
if($query->num_rows > 0){
    $delimiter = ",";
    $filename = "$name/" . date('Y-m-d') . ".csv";
    //create a file pointer for FTP export
    // $f = fopen('../csv/NOM_FICHIER.csv', 'w');

    // No FTP export
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('TIMESTAMP', 'NAME', 'ID', 'ANGLE', 'TEMPERATURE', 'BATTERY', 'RESETFLAG', 'GRAVITY', 'USERTOKEN', 'INTERVAL', 'RSSI');
    fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
        fputcsv($f, $row, $delimiter);
    }
    //move back to beginning of file
    fseek($f, 0);
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;
?>
