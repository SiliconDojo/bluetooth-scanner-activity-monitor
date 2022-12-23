<?php
//This script creates a report from the MySQL database
//It displays how many Bluetooth devices were discovered during a scan
//Shows the picture taken during the scan
//And displays the timecode

$servername="localhost";
$username="bt_user";
$password="123456";
$db="bluetooth";

$conn = new mysqli($servername,$username,$password,$db);

$sql = "select * from log order by id desc";
$result = $conn->query($sql);
$key_array = array();

echo "<div style='background-color:lightgrey;'>";
while($row = $result->fetch_assoc()){
    $bt_all = $row['bt_list'];
    $address = explode(",",$bt_all);
    $key_array = array_flip($address);

    echo "<div style='display:inline-block; margin:10px;'>";
    echo "<h3>Scan: ".date('m/d/Y H:i:s',$row['timestamp'])."</h3>";
    echo "<img style='width:200px;' src='".$row['pic_name']."'>";
    echo "<br>";
    
    echo "<strong>Devices Found: ".count($key_array)."</strong><br>";

    echo "</div>";
}

$sql = "select bt_list from log";
$result = $conn->query($sql);

$key_array2 = array();
$scans = 0;
while($row = $result->fetch_assoc()){
    $bt_all = $row['bt_list'];
    $address = explode(",",$bt_all);
    foreach($address as $x){
        if(!array_key_exists($x, $key_array2)){
            $key_array2[$x] = 1;
        } else {
            $key_array2[$x] = $key_array2[$x] +1;
        }
    }
$scans++;
}

$conn->close();

?>