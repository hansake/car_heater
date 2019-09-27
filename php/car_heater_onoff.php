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
