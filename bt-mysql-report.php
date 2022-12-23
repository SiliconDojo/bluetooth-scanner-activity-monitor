<?php
//This script tries to visualize the number of Bluetooth devices discovered
//during differnt time spans

$servername="localhost";
$username="bt_user";
$password="123456";
$db="bluetooth";

$conn = new mysqli($servername,$username,$password,$db);

echo "<div>";

//Create Report that shows results for each MINUTE
echo "<div style='display:inline-block; margin-right:50px;'>";
$sql = "select * from log order by id desc";
$result = $conn->query($sql);

echo "<h2> BT Devices Detected per MINUTE</h2>";
echo "<table>
    <tr><th>Day/ Minute</th><th># of Devices</th><th></th></tr>";
while($row = $result->fetch_assoc()){    
    $bt_all = $row['bt_list'];
    $address = explode(",",$bt_all);
    $count = count($address);
    echo "<tr><td>";
    echo date('d H:i:s',$row['timestamp'])."</td>";
    echo "<td>$count</td>";
    echo "<td>";
    for($x = 0; $x <= $count; $x++){
        echo "|";
    }
    echo "</td></tr>";
} 
echo "</table>";
echo "</div>";

//Create Report that shows results for each HOUR
echo "<div style='display:inline-block; margin-right:50px;'>";
echo "<h2> BT Devices Detected per HOUR</h2>";
$sql = "select * from log order by id desc";
$result = $conn->query($sql);
$key_array = array();

while($row = $result->fetch_assoc()){    
    $time_code = date('d-H',$row['timestamp']);
    $bt_all = $row['bt_list'];
    $address = explode(",",$bt_all);
    $count = count($address);
    $key_array[$time_code] = $key_array[$time_code] + $count;
}

echo "<table>
    <tr><th>Day/ Hour</th><th># of Devices</th><th></th></tr>";
    foreach($key_array as $key => $value){
        //$value2 divides total BT devices detected by approx. number of scans per hour
        $value2 = intval($value/47);
        echo "<tr><td>$key</td><td>$value2</td><td>";
        for($x=0; $x <= $value2; $x++){
            if($x >= min($key_array)/60){
                echo "|";
            }    
        }
        echo "</td></tr>";
    }
echo "</table>"; 
echo "</div>";

//Create Report that shows results for each DAY
echo "<div style='display:inline-block; margin-right:50px;'>";
echo "<h2> BT Devices Detected per DAY</h2>";
$sql = "select * from log order by id desc";
$result = $conn->query($sql);
$key_array = array();

while($row = $result->fetch_assoc()){    
    $time_code = date('d',$row['timestamp']);
    $bt_all = $row['bt_list'];
    $address = explode(",",$bt_all);
    $count = count($address);
    $key_array[$time_code] = $key_array[$time_code] + $count;
}

echo "<table>
    <tr><th>Day</th><th># of Devices</th><th></th></tr>";
    foreach($key_array as $key => $value){
        //$value2 divides total BT devices detected by approx. number of scans per hour
        $value2 = intval($value/47);
        echo "<tr><td>$key</td><td>$value2</td><td>";
        //in for loop increment $x by 5 to shrink the size of the chart
        for($x=0; $x <= $value2; $x = $x+5){
            if($x >= min($key_array)/60){
                echo "|";
            }    
        }
        echo "</td></tr>";
    }
echo "</table>"; 

echo "</div>";

//Create Report that shows results for Culumnlative HOUR
echo "<div style='display:inline-block;'>";
echo "<h2> Busiest Hour of Day</h2>";
$sql = "select * from log order by id desc";
$result = $conn->query($sql);
$key_array = array();

while($row = $result->fetch_assoc()){    
    $time_code = date('H',$row['timestamp']);
    $bt_all = $row['bt_list'];
    $address = explode(",",$bt_all);
    $count = count($address);
    $key_array[$time_code] = $key_array[$time_code] + $count;
}
ksort($key_array);

echo "<table>
    <tr><th>Hour</th><th># of Devices</th><th></th></tr>";
    foreach($key_array as $key => $value){
        //$value2 divides total BT devices detected by approx. number of scans per hour
        $value2 = intval($value/47);
        echo "<tr><td>$key</td><td>$value2</td><td>";
        for($x=0; $x <= $value2; $x++){
            if($x >= min($key_array)/60){
                echo "|";
            }    
        }
        echo "</td></tr>";
    }
echo "</table>"; 

echo "</div>";

echo "</div>";
?>