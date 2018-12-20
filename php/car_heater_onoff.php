<?php
/* car_heater_action.php
 *
 * Sets the time when the car is expected to be warm and
 * enables the car heater function.
 * Also called when the car heater function is disabled.
 *
 */
include 'car_heater_params.php';

$carwarmdate = $_POST["car_heat_date"];
$carwarmtime = $_POST["car_heat_time"];
$cariswarm = strtotime($carwarmdate.$carwarmtime);
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=updateuservariable&vname=carWarmReadyTime&vtype=0&vvalue=$cariswarm");

if ($debugprint == "true") {
    echo $cariswarm;
    echo ':';
    echo date('Y-m-d H:i', $cariswarm);
    echo '<br>';
}

$carwarmenable = $_POST["car_heat"];

if ($debugprint == "true") {
    echo $carwarmenable;
    echo '<br>';
}

if ($carwarmenable == "car_heat_enable")
    $ch_enable = 1;
else
    $ch_enable = 0;

$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=updateuservariable&vname=carWarmEnabled&vtype=0&vvalue=$ch_enable");

if ($debugprint == "false")
    goback();
?>

pi@domoticz-rpi:/var/www/html/new $ ls
car_heater_action.php  car_heater_onoff.php  car_heater_params.php  car_heater.php  car_heater_status.php
pi@domoticz-rpi:/var/www/html/new $ cat car_heater_onoff.php
<?php
/* car_heater_on_off.php
 *
 * Switches the car heater power socket on or off.
 * The car heater timer  function is also disabled.
 *
 */
include 'car_heater_params.php';

/* Disable car heater timer when switching heater on or off */
$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=updateuservariable&vname=carWarmEnabled&vtype=0&vvalue=0");

$onoff = $_POST["heateronoff"];

if ($debugprint == "true") {
    echo "Heater to be switched: ";
    echo $onoff;
    echo '<br>';
}

$json_string = file_get_contents($domosysapi."/json.htm?type=command&param=switchlight&idx=".$cWswitchIdx."&switchcmd=".$onoff);

if ($debugprint == "false")
    goback();
?>
