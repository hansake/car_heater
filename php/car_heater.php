<?php
/* car_heater.php
 *
 * Car heater main web page
 *
 */
include 'car_heater_params.php';
?>

<!DOCTYPE html>
<html>
<head>

</head>
<title>Car Heater</title>
<body>
<h1>Car Heater</h1>

<?php
/* Get on/off status of heater switch outlet */
$json_string = file_get_contents($domosysapi."/json.htm?type=devices&rid=".$cWswitchIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatonoff = $parsed_json['Status'];
if ($debugprint == "true")
    echo "Heater is: ".$heatonoff."\n<br>";

/* Get outside temperature */
$json_string = file_get_contents($domosysapi."/json.htm?type=devices&rid=".$cWouttempIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$outtemp = $parsed_json['Temp'];
if ($debugprint == "true")
    echo "Outside temperature is: ".$outtemp." &deg;C\n<br>";

/* Get User Variable: carWarmEnabled */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWEnabledIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatenabled = $parsed_json['Value'];
if ($debugprint == "true")
    echo "Heater enable is: ".$heatenabled."\n<br>";
if ($heatenabled == 0) {
    $dischecked = "checked";
    $enchecked = "";
} else {
    $enchecked = "checked";
    $dischecked = "";
}

/* Get User Variable: carWarmReadyTime */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWReadyTimeIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heattime = $parsed_json['Value'];
$ch_date = date("Y-m-d", $heattime);
$ch_time = date("H:i", $heattime);
if ($debugprint == "true")
    echo "Heater warm time is: ".$heattime." = ".$ch_date." ".$ch_time."\n<br>";

/* Get User Variable: carWarmStartTime */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=getuservariable&idx=".$cWStartTimeIdx);
$parsed_json = json_decode($json_string, true);
$parsed_json = $parsed_json['result'][0];
$heatstarttime = $parsed_json['Value'];
$chst_date = date("Y-m-d", $heatstarttime);
$chst_time = date("H:i", $heatstarttime);
if ($debugprint == "true")
    echo "Heater start time is: ".$heatstarttime."\n<br>";
?>

<?php
echo "<h2>";
echo "Car heater is: ".$heatonoff.". Outside temperature is: ".$outtemp." &deg;C. ";
if ($heatenabled == 1) {
  echo "Heater timer is enabled.";
} else {
  echo "Heater timer is disabled.";
}
echo "</h2>";
if ($heatstarttime > 0) {
  echo "<h2>";
  echo "Start heater at: ";
  echo $chst_date;
  echo " ";
  echo $chst_time;
  echo "</h2>";
}
?>
<form action="car_heater_action.php" method="post">
<fieldset>
<legend style="font-size:<?php echo $textsizevw;?>vw;">Car will be warm at: </legend>
<input style="font-size:<?php echo $textsizevw;?>vw;" type="date" name="car_heat_date" value=<?php echo $ch_date;?>>
<input style="font-size:<?php echo $textsizevw;?>vw;" type="time" name="car_heat_time" value=<?php echo $ch_time;?>>
</fieldset>
<button style="font-size:<?php echo $textsizevw;?>vw;" type="submit" name="car_heat" value="car_heat_enable">
Set time and enable timer
</button>
<button style="font-size:<?php echo $textsizevw;?>vw;" type="submit" name="car_heat" value="car_heat_disable">
Disable timer
</button>
</form>
<br>
<form action="car_heater_onoff.php" method="post">
<button style="font-size:<?php echo $textsizevw;?>vw;" name="heateronoff" type="submit" value="On">
Heater On
</button>
<button style="font-size:<?php echo $textsizevw;?>vw;" name="heateronoff" type="submit" value="Off">
Heater Off
</button>
</form>
<br>
<a href="car_heater_status.php">Status and variables</a>
</body>
</html>
