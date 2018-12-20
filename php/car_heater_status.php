<?php
/* car_heater_status.php
 *
 * Show device status and user variables.
 *
 */

include 'car_heater_params.php';
?>

<!DOCTYPE html>
<html>
<head>

</head>
<title>Car Heater Timer Status</title>
<body>
<h1>Car Heater</h1>
<?php
echo "<h2>Status</h2>";

/* Get on/off status of heater switch */
$json_string = file_get_contents($domosysapi."/json.htm?type=devices&rid=".$cWswitchIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatonoff = $parsed_json['Status'];
echo "Heater is: ".$heatonoff."\n<br>";

/* Get outside temperature */
$json_string = file_get_contents($domosysapi."/json.htm?type=devices&rid=".$cWouttempIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$outtemp = $parsed_json['Temp'];
echo "Outside temperature is: ".$outtemp." &deg;C\n<br>";

echo "<h2>User Variables</h2>";

/* Get User Variable: carWarmEnabled */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWEnabledIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatenabled = $parsed_json['Value'];
echo "Heater enable (carWarmEnabled): ".$heatenabled."\n<br>";

/* Get User Variable: carWarmReadyTime */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWReadyTimeIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heattime = $parsed_json['Value'];
echo "Time when the car is expected to be warm (carWarmReadyTime): ".$heattime." = ";
echo date("Y-m-d ", $heattime);
echo date("H:i", $heattime);
echo "<br>";

/* Get User Variable: carWarmStartTime */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWStartTimeIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heattime = $parsed_json['Value'];
echo "Time when the car heater will start (carWarmStartTime): ".$heattime;
if ($heattime == 0) {
    echo " - Heating start time not set";
} else {
    echo date(" = Y-m-d ", $heattime);
    echo date("H:i", $heattime);
}
echo "<br>";

/* Get User Variable: carWarmTempStart */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWTempStartIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatstarttemp = $parsed_json['Value'];
echo "Temperature below which the heater will start (carWarmTempStart): ".$heatstarttemp." &deg;C\n<br>";

/* Get User Variable: carWarmMinus5Minutes */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWMinus5MinutesIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatminutes = $parsed_json['Value'];
echo "Heater start before expected warm time at -5 &deg;C (carWarmMinus5Minutes): ".$heatminutes." minutes\n<br>";
?>

</body>
</html>
