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
